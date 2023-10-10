<?php

namespace App\Services\API;

use GuzzleHttp\Client;

class ApiAuthService
{
    protected Client $client;

    public function authenticate($username, $password)
    {
        try {
            $this->client = new Client([
                'base_uri' => 'https://online.moysklad.ru/api/remap/1.2/',
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
                ],
                'verify' => false,
            ]);

            $response = $this->client->get('entity/employee');

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return $errorMessage;
        }
    }
}
