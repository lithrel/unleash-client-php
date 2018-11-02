<?php
namespace C5A\Unleash\Provider\EndPointProvider;

use C5A\Unleash\Interfaces\EndPointProviderInterface;

class V2 implements EndPointProviderInterface
{
    private static $actionMap = [
        // Features
        'getFeatures' => ['GET', '/features'],
        'getFeature' => ['GET', '/features/%s'],
        // Register
        'registerClient' => ['POST', '/client/register'],
        // Metrics
        'registerMetric' => ['POST', '/client/metrics'],
    ];

    /**
     * @param string $actionName
     * @param array $args
     * @return array
     */
    public function get(string $actionName, ...$args): array
    {
        return self::$actionMap[$actionName];
    }
}