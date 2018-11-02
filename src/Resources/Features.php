<?php
namespace C5A\Unleash\Resources;

use function count;

class Features implements \JsonSerializable
{
    /**
     * @var \C5A\Unleash\Resources\Feature[]
     */
    private $features;

    /**
     * @param string $featureName
     * @param \C5A\Unleash\Resources\Feature $feature
     */
    public function add(string $featureName, Feature $feature): void
    {
        $this->features[$featureName] = $feature;
    }

    /**
     * @param string $featureName
     * @return \C5A\Unleash\Resources\Feature
     */
    public function get(string $featureName): Feature
    {
        return $this->features[$featureName];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->features);
    }

    /**
     * @return Feature[]
     */
    public function getAll(): array
    {
        return $this->features;
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
        return array_values($this->features);
    }
}
