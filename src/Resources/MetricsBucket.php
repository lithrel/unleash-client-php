<?php
namespace C5A\Unleash\Resources;

class MetricsBucket implements \JsonSerializable
{
    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $stop;

    /**
     * @var array
     */
    private $toggles;

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $stop
     * @param array $toggles
     */
    public function __construct(\DateTimeInterface $start, \DateTimeInterface $stop, array $toggles)
    {
        $this->start = $start;
        $this->stop = $stop;
        $this->toggles = $toggles;
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
            'start' => $this->start->format(),
            'stop' => $this->stop->format(),
            'toggles' => $this->toggles,
        ];
    }
}