<?php
namespace C5A\Unleash\Interfaces;

interface MetricsInterface
{
    public function registerUsage(string $featureName, bool $isEnabled): bool;
}