<?php

namespace Niceperson\Truelayer;

use Exception;
use GuzzleHttp\Client;

class Request extends Client
{
    /**
     * Make remote HTTP request
     *
     * @param string $endpoint Endpoint for request
     * @param string $method   Method (GET, POST) to use for request
     * @param array  $data     Data to send (header, body etc)
     *
     * @return array
     */
    public function makeRequest(string $endpoint, string $method, array $data = []) : array
    {
        try {
            $response = $this->request($method, $endpoint, $data);
            return [
                'error' => false,
                'statusCode' => $response->getStatusCode(),
                'reason' => $response->getReasonPhrase(),
                'body' => json_decode($response->getBody(), true)
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'reason' => $e->getMessage()
            ];
        }
    }
}
