<?php
namespace C5A\Unleash\Interfaces;

interface StrategyMapInterface
{
    public function get(string $name, ...$parameters): StrategyInterface;
}
