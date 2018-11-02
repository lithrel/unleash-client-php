<?php
namespace C5A\Unleash\Resources;

class Metrics implements \JsonSerializable
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
     * @var \C5A\Unleash\Resources\MetricsBucket
     */
    private $bucket;

    /**
     * @param string $appName
     * @param string $instanceId
     * @param \C5A\Unleash\Resources\MetricsBucket $bucket
     */
    public function __construct(string $appName, string $instanceId, MetricsBucket $bucket)
    {
        $this->appName = $appName;
        $this->instanceId = $instanceId;
        $this->bucket = $bucket;
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
            'bucket' => $this->bucket,
        ];
    }
}
