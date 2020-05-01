<?php

namespace Niceperson\Truelayer;

use Exception;
use GuzzleHttp\Client;

class Request extends Client
{
    public function makeRequest(string $endpoint, string $method, array $data = [])
    {
        try {
            $response = $this->request($method, $endpoint, $data);
            return [
                'statusCode' => $response->getStatusCode(),
                'reason' => $response->getReasonPhrase(),
                'body' => json_decode($response->getBody(), true)
            ];
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
