<?php

namespace App\Consumers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class PanagoraConsumer
{
    public function makeRequest(string $method, string $path)
    {
        $panagora_api = new Client([
            'base_uri' => env('PANAGORA_URI'),
            'headers' => [
                'Authorization' => 'Bearer ' . env('PANAGORA_AUTH_TOKEN')
            ],
            'http_errors' => false
        ]);

        $external_response = $panagora_api->request($method, $path);

        $data = json_decode($external_response->getBody()->getContents());
        return $data;
    }

    public function makeConcurrentRequests(array $concurrents, $method)
    {
        $panagora_api = new Client([
            'base_uri' => env('PANAGORA_URI'),
            'headers' => [
                'Authorization' => 'Bearer ' . env('PANAGORA_AUTH_TOKEN')
            ],
            'http_errors' => false
        ]);
        $promises = [];

        foreach ($concurrents as $key => $concurrent) {
            $promises[$concurrent['key']] = $panagora_api->getAsync($concurrent['path']);
        }

        $responses = Promise\Utils::unwrap($promises);

        return array_map(function ($response) {
            return json_decode($response->getBody()->getContents());
        },$responses);
    }
}
