<?php

namespace App\Services\API;

use GuzzleHttp\Client;

class ApiGroupsService
{
    protected readonly Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://online.moysklad.ru/api/remap/1.2/',
            'headers' => [
                'Authorization' => 'Bearer '.config('clothes.token'),
            ],
            'verify' => false,
        ]);
    }

    public function getServices()
    {
        $response = $this->client->get('entity/productfolder');
        return json_decode($response->getBody(), true);
    }
}
