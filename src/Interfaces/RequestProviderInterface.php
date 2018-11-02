<?php
namespace C5A\Unleash\Interfaces;

use Psr\Http\Message\RequestInterface;

interface RequestProviderInterface
{
    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getFeatures(): RequestInterface;

    /**
     * @param string $featureName
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getFeature(string $featureName): RequestInterface;

    /**
     * @param \JsonSerializable $client
     * @return \Psr\Http\Message\RequestInterface
     */
    public function registerClient(\JsonSerializable $client): RequestInterface;

    /**
     * @param \JsonSerializable $metrics
     * @return \Psr\Http\Message\RequestInterface
     */
    public function registerMetrics(\JsonSerializable $metrics): RequestInterface;
}
