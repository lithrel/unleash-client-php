<?php
namespace C5A\Unleash\Interfaces;

interface EndPointProviderInterface
{
    public function get(string $actionName, ...$args): array;
}