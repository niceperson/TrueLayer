<?php

namespace Niceperson\Truelayer;

use Exception;
use GuzzleHttp\Client;

class Request extends Client
{
    public function makeRequest(string $base, string $endpoint, string $method, array $data = []) : array
    {

        try {
            $response = $this->request($method, $base . $endpoint, $data);
            return [
                'error' => false,
                'statusCode' => $response->getStatusCode(),
                'reason' => $response->getReasonPhrase(),
                'body' => json_decode($response->getBody(), true)
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}