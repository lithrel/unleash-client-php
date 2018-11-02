<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\ContextInterface;
use C5A\Unleash\Interfaces\StrategyInterface;

class GradualRolloutUserIdStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $percentage;

    /**
     * @var
     */
    private $groupId;

    /**
     * @param int $percentage
     * @param string $groupId
     */
    public function __construct(int $percentage, string $groupId = '')
    {
        $this->percentage = $percentage;
        $this->groupId = $groupId;
    }

    /**
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     */
    public function isEnabled(ContextInterface $context): bool
    {
        if ($this->percentage <= 0 || !$context->getUserId()) {
            return false;
        }

        return (new HasherNormalizer())->hashAndNormalize($context->getUserId(), $this->groupId) <= $this->percentage;
    }
}
