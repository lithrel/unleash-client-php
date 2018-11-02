<?php
namespace C5A\Unleash\Provider\EndPointProvider;

use C5A\Unleash\Interfaces\EndPointProviderInterface;
use function GuzzleHttp\Psr7\str;

class V3 implements EndPointProviderInterface
{
    private static $actionMap = [
        // Features
        'getFeatures' => ['GET', '/client/features'],
        'getFeature' => ['GET', '/client/features/%s'],
        // Register
        'registerClient' => ['POST', '/client/register'],
        // Metrics
        'registerMetric' => ['POST', '/client/metrics'],

        // ADMIN
        'updateFeature' => ['PUT', '/admin/features/%s'],
        'createFeature' => ['POST', '/admin/features'],
        'archiveFeature' => ['DELETE', '/admin/features/%s'],
        'reviveFeature' => ['POST', '/admin/archive/revive'],
    ];

    /**
     * @param string $actionName
     * @param array $args
     * @return array
     */
    public function get(string $actionName, ...$args): array
    {
        if (!isset(self::$actionMap[$actionName])) {
            throw new \RuntimeException('Action not mapped');
        }
        $endpoint = self::$actionMap[$actionName];
        if (!empty($args)) {
            $endpoint[1] = sprintf($endpoint[1], ...$args);
        }
        return $endpoint;
    }
}