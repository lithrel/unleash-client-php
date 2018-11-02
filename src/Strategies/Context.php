<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\ContextInterface;

/**
 * Class Context
 * @package C5A\Unleash\Strategies
 * @see https://github.com/Unleash/unleash/blob/master/docs/activation-strategies.md
 */
class Context implements ContextInterface
{
    private $userId;
    private $sessionId;
    private $remoteAddress; // ip
    private $hostname;
    private $properties;

    /**
     * @return mixed
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @return mixed
     */
    public function getRemoteAddress(): string
    {
        return $this->remoteAddress;
    }

    /**
     * @return mixed
     */
    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getProperty(string $name): ?string
    {
        return $this->properties[$name] ?? null;
    }
}
