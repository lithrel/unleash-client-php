<?php
namespace C5A\Unleash\Resources;

class Feature implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var \C5A\Unleash\Resources\Strategies
     */
    private $strategies;

    /**
     * @var \C5A\Unleash\Resources\Strategy
     */
    private $strategy;

    /**
     * @var
     */
    private $createdAt;

    /**
     * @param array $data
     * @return \C5A\Unleash\Resources\Feature
     */
    public function hydrate(array $data): self
    {
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->enabled = (bool) ($data['enabled'] ?? false);

        $this->strategies = new Strategies();
        if (!empty($data['strategies'])) {
            foreach ($data['strategies'] as $strategyData) {
                $strategy = (new Strategy())->hydrate($strategyData);
                $this->strategies->add($strategy->getName(), $strategy);
            }
        }

        if (!empty($data['strategy'])) {
            $this->strategy = (new Strategy())->hydrate(['name' => $data['strategy'], 'parameters' => $data['parameters'] ?? []]);
        }

        $this->createdAt = $data['createdAt'] ?? [];

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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return \C5A\Unleash\Resources\Strategies
     */
    public function getStrategies(): Strategies
    {
        return $this->strategies;
    }

    /**
     * @return \C5A\Unleash\Resources\Strategy
     */
    public function getStrategy(): Strategy
    {
        return $this->strategy;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'strategies' => $this->strategies,
            'createdAt' => $this->createdAt,
        ];

        if (null !== $this->strategies) {
            $data['strategies'] = $this->strategies;
        }

        if (null !== $this->strategy) {
            $data['strategy'] = $this->strategy->getName();
            $data['parameters'] = $this->strategy->getParameters();
        }

        return $data;
    }
}