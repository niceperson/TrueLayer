<?php

namespace Niceperson\Truelayer;

use Niceperson\Truelayer\Request;

class Payment
{
    private $request;
    private $token;

    public function __construct(Request $request, string $token)
    {
        $this->request = $request;
        $this->token = $token;
    }

    private function getAuthHeader() : array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $this->token)
        ];
    }

    private function getJsonHeader() : array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    private function getPaymentIdEndpoint(string $endpoint, string $paymentId)
    {
        return sprintf($endpoint, $paymentId);
    }

    public function createPayment(
        array $payload,
        string $endpoint = '/single-immediate-payments',
        string $method = 'POST'
    ) : array {
        $data = [
            'headers' => array_merge($this->getAuthHeader(), $this->getJsonHeader()),
            'body' => json_encode($payload)
        ];
        return $this->request->makeRequest($endpoint, $method, $data);
    }

    public function getPaymentStatus(
        string $paymentId,
        string $endpoint = '/single-immediate-payments/%s',
        string $method = 'GET'
    ) : array {
        $data = ['headers' => $this->getAuthHeader()];
        return $this->request->makeRequest($this->getPaymentIdEndpoint($endpoint, $paymentId), $method, $data);
    }
}
