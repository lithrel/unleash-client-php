<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\ContextInterface;
use C5A\Unleash\Interfaces\StrategyInterface;

class GradualRolloutRandomStrategy implements StrategyInterface
{
    /**
     * @var int
     */
    private $percentage;

    /**
     * @param int $percentage
     */
    public function __construct(int $percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     */
    public function isEnabled(ContextInterface $context): bool
    {
        if ($this->percentage <= 0) {
            return false;
        }

        try {
            $pos = random_int(0, 100);
        } catch (\Exception $e) {
            return false;
        }

        return $pos > 0 && $pos <= $this->percentage;
    }
}