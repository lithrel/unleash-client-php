<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\ContextInterface;
use C5A\Unleash\Interfaces\StrategyInterface;
use function in_array;

class RemoteAddressStrategy implements StrategyInterface
{
    /**
     * @var string[]
     */
    private $ips;

    /**
     * @param string[] $ips
     */
    public function __construct(array $ips)
    {
        $this->ips = $ips;
    }

    /**
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     */
    public function isEnabled(ContextInterface $context): bool
    {
        if (!$context->getRemoteAddress()) {
            return false;
        }
        return in_array($context->getRemoteAddress(), $this->ips, true);
    }
}
