<?php
namespace C5A\Unleash;

use C5A\Unleash\Interfaces\MetricsInterface;
use C5A\Unleash\Resources;
use Psr\SimpleCache\CacheInterface;
use function microtime;
use function number_format;
use function time;

class Metrics implements MetricsInterface
{
    /**
     * @var string
     */
    private $appName;

    /**
     * @var string
     */
    private $instanceId;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    /**
     * @var \C5A\Unleash\UnleashClient
     */
    private $client;

    /**
     * @var string
     */
    private const CACHE_KEY = 'unleash-metrics';

    /**
     * @var int
     */
    private const FLUSH_FREQUENCY = 60 * 10;

    /**
     * @var string
     */
    private const START_KEY = '__start';

    /**
     * @var array
     */
    private const STATS_BASE = ['yes' => 0, 'no' => 0];

    /**
     * @param string $appName
     * @param string $instanceId
     * @param \Psr\SimpleCache\CacheInterface $cache
     * @param \C5A\Unleash\UnleashClient $client
     */
    public function __construct(string $appName, string $instanceId, CacheInterface $cache, UnleashClient $client)
    {
        $this->appName = $appName;
        $this->instanceId = $instanceId;
        $this->cache = $cache;
        $this->client = $client;
    }

    /**
     * @param string $featureName
     * @param bool $enabled
     * @return bool
     */
    public function registerUsage(string $featureName, bool $enabled): bool
    {
        try {
            $stats = $this->cache->get(self::CACHE_KEY, null) ?? $this->initStats();
            // Time to flush
            if ($this->isFlushTime($stats[self::START_KEY]) && $this->flush()) {
                $stats = $this->cache->get(self::CACHE_KEY, null) ?? $this->initStats();
            }
            if (empty($stats[$featureName])) {
                $stats[$featureName] = self::STATS_BASE;
            }

            $enabled ? ++$stats[$featureName]['yes'] : ++$stats[$featureName]['no'];
            return $this->cache->set(self::CACHE_KEY, $stats);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @return array
     */
    private function initStats(): array
    {
        $stats[self::START_KEY] = number_format(microtime(true), 6, '.', '');
        return $stats;
    }

    /**
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function sendToServer(): bool
    {
        $stats = $this->cache->get(self::CACHE_KEY);
        $start = $stats[self::START_KEY];
        unset($stats[self::START_KEY]);

        $this->client->registerMetrics(
            new Resources\Metrics(
                $this->appName,
                $this->instanceId,
                new Resources\MetricsBucket(
                    \DateTime::createFromFormat('U.u', $start),
                    \DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', '')),
                    $stats
                )
            )
        );

        return true;
    }

    /**
     * @return bool
     */
    private function flush(): bool
    {
        try {
            return $this->sendToServer() && $this->resetUsage();
        } catch (\Psr\SimpleCache\InvalidArgumentException | \Psr\Http\Client\ClientExceptionInterface $e) {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function resetUsage(): bool
    {
        return $this->cache->set(self::CACHE_KEY, $this->initStats());
    }

    /**
     * @param $start
     * @return bool
     */
    private function isFlushTime($start): bool
    {
        return $start + self::FLUSH_FREQUENCY > time();
    }
}