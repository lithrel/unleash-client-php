<?php
namespace C5A\Unleash\Provider;

use C5A\Unleash\Config;
use C5A\Unleash\Interfaces\EndPointProviderInterface;
use C5A\Unleash\Interfaces\RequestProviderInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class RequestProvider implements RequestProviderInterface
{
    /**
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var \Psr\Http\Message\UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var UriInterface
     */
    private $apiUri;

    /**
     * @var string
     */
    private $appName;

    /**
     * @var string
     */
    private $instanceId;

    /**
     * @var \C5A\Unleash\Interfaces\EndPointProviderInterface
     */
    private $endPoint;

    /**
     * @param \Psr\Http\Message\UriInterface $apiUri
     * @param string $appName
     * @param string $instanceId
     * @param \C5A\Unleash\Interfaces\EndPointProviderInterface $endPoint
     * @param \Psr\Http\Message\RequestFactoryInterface $requestFactory
     * @param \Psr\Http\Message\StreamFactoryInterface $streamFactory
     * @param \Psr\Http\Message\UriFactoryInterface $uriFactory
     */
    public function __construct(
        UriInterface $apiUri,
        string $appName,
        string $instanceId,
        EndPointProviderInterface $endPoint,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        UriFactoryInterface $uriFactory
    )
    {
        $this->apiUri = $apiUri;
        $this->appName = $appName;
        $this->instanceId = $instanceId;
        $this->endPoint = $endPoint;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @param string $actionName
     * @param array $args
     * @return array [method, UriInterface]
     */
    private function endpoint(string $actionName, ...$args): array
    {
        [$method, $endpoint] = $this->endPoint->get($actionName, ...$args);
        return [
            $method,
            $this->uriFactory->createUri($this->apiUri->__toString() . $endpoint)
        ];
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/feature-toggles-api.md
     */
    public function getFeatures(): RequestInterface
    {
        return $this->requestFactory->createRequest(...$this->endpoint('getFeatures'))
            ->withHeader('UNLEASH-APPNAME', $this->appName)
            ->withHeader('UNLEASH-INSTANCEID', $this->instanceId);
    }

    /**
     * @param string $featureName
     * @return \Psr\Http\Message\RequestInterface
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/feature-toggles-api.md
     */
    public function getFeature(string $featureName): RequestInterface
    {
        return $this->requestFactory->createRequest(...$this->endpoint('getFeature', $featureName))
            ->withHeader('UNLEASH-APPNAME', $this->appName)
            ->withHeader('UNLEASH-INSTANCEID', $this->instanceId);
    }

    /**
     * @param \JsonSerializable $client
     * @return \Psr\Http\Message\RequestInterface
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/register-api.md
     */
    public function registerClient(\JsonSerializable $client): RequestInterface
    {
        return $this->requestFactory->createRequest(...$this->endpoint('registerClient'))
            ->withHeader('Content-type', 'application/json')
            ->withBody($this->streamFactory->createStream(json_encode($client)));
    }

    /**
     * @param \JsonSerializable $metrics
     * @return \Psr\Http\Message\RequestInterface
     * @see https://github.com/Unleash/unleash/blob/master/docs/api/client/metrics-api.md
     */
    public function registerMetrics(\JsonSerializable $metrics): RequestInterface
    {
        return $this->requestFactory->createRequest(...$this->endpoint('registerMetrics'))
            ->withHeader('Content-type', 'application/json')
            ->withBody($this->streamFactory->createStream(json_encode($metrics)));
    }
}