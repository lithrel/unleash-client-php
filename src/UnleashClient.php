<?php
namespace C5A\Unleash;

use C5A\Unleash\Interfaces\RequestProviderInterface;
use C5A\Unleash\Resources;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function is_array;
use function json_decode;

class UnleashClient
{
    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    private $httpClient;

    /**
     * @var \C5A\Unleash\Provider\RequestProvider
     */
    private $requestProvider;

    /**
     * @var ResponseInterface
     */
    private $lastResponse;

    /**
     * UnleashClient constructor.
     * @param \Psr\Http\Client\ClientInterface $httpClient
     * @param \C5A\Unleash\Interfaces\RequestProviderInterface $requestProvider
     */
    public function __construct(ClientInterface $httpClient, RequestProviderInterface $requestProvider)
    {
        $this->httpClient = $httpClient;
        $this->requestProvider = $requestProvider;
    }

    /**
     * @return \C5A\Unleash\Resources\Features
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/feature-toggles-api.md
     */
    public function getFeatures(): ?Resources\Features
    {
        $data = $this->request($this->requestProvider->getFeatures());
        if (!is_array($data) || !$this->lastRequestWasSuccessful()) {
            return null;
        }

        $features = new Resources\Features();
        foreach ($data['features'] as $featureData) {
            $feature = (new Resources\Feature())->hydrate($featureData);
            $features->add($feature->getName(), $feature);
        }
        return $features;
    }

    /**
     * @param string $featureName
     * @return \C5A\Unleash\Resources\Feature|null
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/feature-toggles-api.md
     */
    public function getFeature(string $featureName): ?Resources\Feature
    {
        $data = $this->request($this->requestProvider->getFeature($featureName));
        return is_array($data) && $this->lastRequestWasSuccessful()
            ? (new Resources\Feature())->hydrate($data)
            : null;
    }

    /**
     * @param \C5A\Unleash\Resources\Client $client
     * @return mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://github.com/Unleash/unleash/blob/v3.1.1/docs/api/client/register-api.md
     */
    public function registerClient(Resources\Client $client)
    {
        return $this->request($this->requestProvider->registerClient($client));
    }

    /**
     * @param \C5A\Unleash\Resources\Metrics $metrics
     * @return mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @see https://github.com/Unleash/unleash/blob/master/docs/api/client/metrics-api.md
     */
    public function registerMetrics(Resources\Metrics $metrics)
    {
        return $this->request($this->requestProvider->registerMetrics($metrics));
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->lastResponse;
    }

    /**
     * @return bool
     */
    public function lastRequestWasSuccessful(): bool
    {
        return $this->lastResponse && $this->lastResponse->getStatusCode() < 400;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function request(RequestInterface $request)
    {
        $this->lastResponse = $this->httpClient->sendRequest($request);
        $responseText = $this->lastResponse->getBody()->__toString();
        return json_decode($responseText, true) ?? $responseText;
    }
}