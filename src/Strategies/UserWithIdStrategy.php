<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\ContextInterface;
use C5A\Unleash\Interfaces\StrategyInterface;
use function in_array;

class UserWithIdStrategy implements StrategyInterface
{
    /**
     * @var string[]
     */
    private $userIds;

    /**
     * @param string[] $userIds
     */
    public function __construct(array $userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     */
    public function isEnabled(ContextInterface $context): bool
    {
        if (!$context->getUserId()) {
            return false;
        }
        return in_array($context->getUserId(), $this->userIds, true);
    }
}
