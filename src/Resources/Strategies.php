<?php
namespace C5A\Unleash\Resources;

class Strategies implements \JsonSerializable
{
    /**
     * @var \C5A\Unleash\Resources\Strategy[]
     */
    private $strategies;

    /**
     * @param string $strategyName
     * @param \C5A\Unleash\Resources\Strategy $strategy
     */
    public function add(string $strategyName, Strategy $strategy): void
    {
        $this->strategies[$strategyName] = $strategy;
    }

    /**
     * @return \C5A\Unleash\Resources\Strategy[]
     */
    public function getAll(): array
    {
        return $this->strategies;
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
        return array_values($this->strategies);
    }
}
