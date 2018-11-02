<?php
namespace C5A\Unleash\Strategies;

use C5A\Unleash\Interfaces\StrategyInterface;
use C5A\Unleash\Interfaces\StrategyMapInterface;

class StrategyMap implements StrategyMapInterface
{
    /**
     * @var string
     */
    private const STRATEGY_NAMESPACE = __NAMESPACE__ . '\\';

    /**
     * @param string $name
     * @param mixed ...$parameters
     * @return \C5A\Unleash\Interfaces\StrategyInterface
     */
    public function get(string $name, ...$parameters): StrategyInterface
    {
        $className = self::STRATEGY_NAMESPACE . ucfirst($name) . 'Strategy';
        if (!class_exists($className)) {
            throw new \RuntimeException(sprintf('Strategy %s : %s does not exists', $name, $className));
        }

        return new $className(...$parameters);
    }
}
