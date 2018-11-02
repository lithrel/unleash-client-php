<?php
namespace C5A\Unleash;

use C5A\Unleash\Interfaces\EndPointProviderInterface;
use C5A\Unleash\Interfaces\MetricsInterface;
use C5A\Unleash\Interfaces\RequestProviderInterface;
use C5A\Unleash\Interfaces\StrategyMapInterface;
use C5A\Unleash\Provider\EndPointProvider;
use C5A\Unleash\Provider\RequestProvider;
use C5A\Unleash\Strategies\StrategyMap;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\SimpleCache\CacheInterface;

class Factory
{
    /**
     * @param string $apiUri
     * @param string $appName
     * @param string $instanceId
     * @param \Psr\Http\Client\ClientInterface $httpClient
     * @param \Psr\Http\Message\RequestFactoryInterface $requestFactory
     * @param \Psr\Http\Message\StreamFactoryInterface $streamFactory
     * @param \Psr\Http\Message\UriFactoryInterface $uriFactory
     * @param \C5A\Unleash\Interfaces\RequestProviderInterface|null $requestProvider
     * @param \C5A\Unleash\Interfaces\EndPointProviderInterface|null $endPoint
     * @param \C5A\Unleash\Interfaces\MetricsInterface|null $metrics
     * @param null|\Psr\SimpleCache\CacheInterface $cache
     * @param \C5A\Unleash\Interfaces\StrategyMapInterface|null $strategyMap
     * @return \C5A\Unleash\Unleash
     */
    public function make(
        string $apiUri,
        string $appName,
        string $instanceId,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory,
        ?RequestProviderInterface $requestProvider = null,
        ?EndPointProviderInterface $endPoint = null,
        ?MetricsInterface $metrics = null,
        ?CacheInterface $cache = null,
        ?StrategyMapInterface $strategyMap = null
    ): Unleash
    {
        $requestProvider = $requestProvider ?? new RequestProvider(
            $uriFactory->createUri($apiUri),
            $appName,
            $instanceId,
            $endPoint ?? new EndPointProvider\V3(),
            $requestFactory,
            $streamFactory,
            $uriFactory
        );
        $client = new UnleashClient($httpClient, $requestProvider);
        $metrics = $metrics ?? ($cache ? new Metrics($appName, $instanceId, $cache, $client) : null);
        $strategyMap = $strategyMap ?? new StrategyMap();

        $identifier = new Identifier($appName, $instanceId);
        return new Unleash($identifier, $client, $cache, $metrics, $strategyMap);
    }
}
