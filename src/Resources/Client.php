<?php
namespace C5A\Unleash\Resources;

class Client implements \JsonSerializable
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
    private $sdkVersion;

    /**
     * @var string[]
     */
    private $strategies;

    /**
     * @var string
     */
    private $started;

    /**
     * @var int
     */
    private $interval;

    public function __construct(
        string $appName,
        string $instanceId,
        string $sdkVersion,
        array $strategies,
        string $started,
        int $interval
    )
    {
        $this->appName = $appName;
        $this->instanceId = $instanceId;
        $this->sdkVersion = $sdkVersion;
        $this->strategies = $strategies;
        $this->started = $started;
        $this->interval = $interval;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'appName' => $this->appName,
            'instanceId' => $this->instanceId,
            'sdkVersion' => $this->sdkVersion,
            'strategies' => $this->strategies,
            'started' => $this->started,
            'interval' => $this->interval,
        ];
    }
}
