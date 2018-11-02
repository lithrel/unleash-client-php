<?php
namespace C5A\Unleash;

use C5A\Unleash\Interfaces\EndPointProviderInterface;

class Config
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
     * @var string
     */
    private $apiUri;

    /**
     * @var \C5A\Unleash\Interfaces\EndPointProviderInterface
     */
    private $endPoint;

    /**
     * Config constructor.
     * @param string $apiUri
     * @param string $appName
     * @param string $instanceId
     * @param \C5A\Unleash\Interfaces\EndPointProviderInterface|null $endpoint
     */
    public function __construct(
        string $apiUri,
        string $appName,
        string $instanceId,
        ?EndPointProviderInterface $endpoint
    )
    {
        $this->apiUri = $apiUri;
        $this->appName = $appName;
        $this->instanceId = $instanceId;
        $this->endPoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * @return string
     */
    public function getApiUri(): string
    {
        return $this->apiUri;
    }

    /**
     * @return \C5A\Unleash\Interfaces\EndPointProviderInterface
     */
    public function getEndPoint(): EndPointProviderInterface
    {
        return $this->endPoint;
    }

    /**
     * @return bool
     */
    public function hasCache(): bool
    {
        return false;
    }
}