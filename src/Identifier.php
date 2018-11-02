<?php
namespace C5A\Unleash;

class Identifier
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
    private $sdkVersion = 'unleash-client-php:1.0.0';

    /**
     * ClientId constructor.
     * @param string $appName
     * @param string $instanceId
     */
    public function __construct(string $appName, string $instanceId)
    {
        $this->appName = $appName;
        $this->instanceId = $instanceId;
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
    public function getSdkVersion(): string
    {
        return $this->sdkVersion;
    }
}