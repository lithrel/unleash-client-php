<?php
namespace C5A\Unleash\Interfaces;

/**
 * Interface ContextInterface
 * @package C5A\Unleash\Interfaces
 * @see https://github.com/Unleash/unleash/blob/master/docs/unleash-context.md
 */
interface ContextInterface
{
    public function getUserId(): ?string;

    public function getSessionId(): ?string;

    public function getRemoteAddress(): ?string;

    public function getHostName(): ?string;

    public function getProperties(): array;

    public function getProperty(string $name): ?string;
}
