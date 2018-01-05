<?php
include '../vendor/autoload.php';

$client = new \Predis\Client([
    'host'   => 'redis',
]);

if (!$client->exists('coins')) {
    $guzzleClient = new \GuzzleHttp\Client();
    $response = $guzzleClient->request('GET', 'https://api.coinmarketcap.com/v1/ticker/?limit=200&convert=EUR');
    $client->set('coins', $response->getBody());
    $client->expireat('coins', strtotime('10 minutes'));
}

header('Content-Type: application/json');
echo $client->get('coins');
