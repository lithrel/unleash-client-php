<?php
include __DIR__ . '/vendor/autoload.php';

$unleash = (new \C5A\Unleash\Factory())->make(
    'http://127.0.0.1:4242/api', // APi URL
    'production', // Application name
    'me4MzE_Wz8V19G6E25yJ', // Instance ID
    new \C5A\Unleash\Adapter\Psr18\Guzzle\Client(),
    new \Http\Factory\Guzzle\RequestFactory(),
    new \Http\Factory\Guzzle\StreamFactory(),
    new \Http\Factory\Guzzle\UriFactory()
);

// Features
echo "\n";
var_dump($features = $unleash->getFeatures());
echo "\n";
var_dump($unleash->getFeature('feature-1'));
echo "\n";
var_dump($unleash->isEnabled('feature-1', new \C5A\Unleash\Strategies\Context()));
echo "\n";


