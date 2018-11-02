<?php
namespace C5A\Unleash;

use C5A\Unleash\Interfaces\ContextInterface;
use C5A\Unleash\Interfaces\MetricsInterface;
use C5A\Unleash\Interfaces\StrategyMapInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Unleash
 */
class Unleash
{
    /**
     * @var string
     */
    public const VERSION = '1.0.0';

    /**
     * @var \C5A\Unleash\UnleashClient
     */
    private $client;

    /**
     * @var null|\Psr\SimpleCache\CacheInterface
     */
    private $cache;

    /**
     * @var \C5A\Unleash\Metrics
     */
    private $metrics;

    /**
     * @var \C5A\Unleash\Identifier
     */
    private $identifier;

    /**
     * @var \C5A\Unleash\Interfaces\StrategyMapInterface|null
     */
    private $strategyMap;

    /**
     * @param \C5A\Unleash\Identifier $identifier
     * @param \C5A\Unleash\UnleashClient $client
     * @param null|\Psr\SimpleCache\CacheInterface $cache
     * @param null|\C5A\Unleash\Interfaces\MetricsInterface $metrics
     * @param \C5A\Unleash\Interfaces\StrategyMapInterface|null $strategyMap
     */
    public function __construct(
        Identifier $identifier,
        UnleashClient $client,
        ?CacheInterface $cache,
        ?MetricsInterface $metrics,
        ?StrategyMapInterface $strategyMap
    )
    {
        $this->identifier = $identifier;
        $this->client = $client;
        $this->cache = $cache;
        $this->metrics = $metrics;
        $this->strategyMap = $strategyMap;

        $this->registerSelf();
    }

    /**
     * @param string $featureName
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     * @see https://github.com/Unleash/unleash/blob/master/docs/client-specification.md#the-basics
     */
    public function isEnabled(string $featureName, ContextInterface $context): bool
    {
        $feature = $this->getFeature($featureName);
        if (!$feature || !$feature->isEnabled()) {
            return false;
        }
        if (!$feature->getStrategies()) {
            return $feature->isEnabled();
        }

        foreach ($feature->getStrategies()->getAll() as $stratDef) {

            $strategy = $this->strategyMap->get($stratDef->getName(), ...array_values($stratDef->getParameters()));
            var_dump($strategy);
            if ($strategy->isEnabled($context)) {
                return true;
            }
        }

        return $feature ? $feature->isEnabled() : false;
    }

    /**
     * @return \C5A\Unleash\Resources\Features|null
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/feature-toggles-api.md
     */
    public function getFeatures(): ?Resources\Features
    {
        if ($features = $this->getFeaturesFromCache()) {
            return $features;
        }

        try {
            $features = $this->client->getFeatures();
            $this->cacheFeatures($features);
            return $features;
        } catch (ClientExceptionInterface $e) {
            return null;
        }
    }

    /**
     * @param string $featureName
     * @return \C5A\Unleash\Resources\Feature|null
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/feature-toggles-api.md
     */
    public function getFeature(string $featureName): ?Resources\Feature
    {
        if ($feature = $this->getFeatureFromCache($featureName)) {
            $this->registerUsage($featureName, $feature->isEnabled());
            return $feature;
        }

        try {
            $feature =  $this->client->getFeature($featureName);
            var_dump($this->client->getLastResponse()->getBody()->__toString());
            $this->registerUsage($featureName, $feature ? $feature->isEnabled() : false);
            return $feature;
        } catch (ClientExceptionInterface $e) {
            return null;
        }
    }

    /**
     * @param string $featureName
     * @param bool $isEnabled
     * @return bool
     */
    private function registerUsage(string $featureName, bool $isEnabled): bool
    {
        return $this->metrics ? $this->metrics->registerUsage($featureName, $isEnabled) : false;
    }

    /**
     * @return bool
     */
    private function registerSelf(): bool
    {
        try {
            $this->client->registerClient(new Resources\Client(
                $this->identifier->getAppName(),
                $this->identifier->getInstanceId(),
                $this->identifier->getSdkVersion(),
                [],
                (new \DateTime())->format(\DateTime::ATOM),
                10000
            ));
            return $this->client->lastRequestWasSuccessful();
        } catch (ClientExceptionInterface $e) {
            return false;
        }
    }

    // CACHE

    /**
     * @return \C5A\Unleash\Resources\Features|null
     */
    private function getFeaturesFromCache(): ?Resources\Features
    {
        if (!$this->cache) {
            return null;
        }

        try {
            return $this->cache->get('unleash-features');
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * @param string $featureName
     * @return \C5A\Unleash\Resources\Feature|null
     */
    private function getFeatureFromCache(string $featureName): ?Resources\Feature
    {
        if (!$this->cache) {
            return null;
        }

        if (!($features = $this->getFeaturesFromCache())) {
            return null;
        }
        return $features[$featureName] ?? null;
    }

    /**
     * @param \C5A\Unleash\Resources\Features $features
     * @return bool
     */
    private function cacheFeatures(?Resources\Features $features): bool
    {
        if (!$this->cache || null === $features) {
            return false;
        }

        try {
            return $this->cache->set('unleash-features', $features);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            return false;
        }
    }
}
