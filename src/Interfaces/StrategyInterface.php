<?php
namespace C5A\Unleash\Interfaces;

/**
 * Interface ContextInterface
 * @package C5A\Unleash\Interfaces
 * @see https://github.com/Unleash/unleash/blob/master/docs/unleash-context.md
 */
interface StrategyInterface
{
    /**
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     */
    public function isEnabled(ContextInterface $context): bool;
}
