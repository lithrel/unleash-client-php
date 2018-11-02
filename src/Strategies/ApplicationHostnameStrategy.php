<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\ContextInterface;
use C5A\Unleash\Interfaces\StrategyInterface;
use function in_array;

class ApplicationHostnameStrategy implements StrategyInterface
{
    /**
     * @var string[]
     */
    private $hostnames;

    /**
     * @param array $hostnames
     */
    public function __construct(array $hostnames)
    {
        $this->hostnames = $hostnames;
    }

    /**
     * @param \C5A\Unleash\Interfaces\ContextInterface $context
     * @return bool
     */
    public function isEnabled(ContextInterface $context): bool
    {
        if (!$context->getHostName()) {
            return false;
        }
        return in_array($context->getHostName(), $this->hostnames, true);
    }
}
