<?php
namespace C5A\Unleash\Resources;

class Strategy implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param array $data
     * @return \C5A\Unleash\Resources\Strategy
     */
    public function hydrate(array $data): self
    {
        $this->name = $data['name'] ?? '';
        $this->parameters = $data['parameters'] ?? [];
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
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
            'name' => $this->name,
            'parameters' => $this->parameters,
        ];
    }
}
