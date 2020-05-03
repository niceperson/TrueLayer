<?php

namespace Niceperson\Truelayer;

use Exception;
use Niceperson\Truelayer\Token;

class Authorization
{
    protected $request;
    protected $credentials;

    public function __construct(Request $request, Credentials $credentials)
    {
        $this->request = $request;
        $this->credentials = $credentials;
    }

    public function getAccessToken(string $code)
    {
        $grant = 'authorization_code';
        $method = 'POST';
        $endpoint = '/connect/token';

        $result = $this->request->makeRequest(
            $endpoint,
            $method,
            [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'grant_type' => $grant,
                    'client_id' => $this->credentials->getClientId(),
                    'client_secret' => $this->credentials->getClientSecret(),
                    'redirect_uri' => $this->credentials->getRedirectUri(),
                    'code' => $code
                ]
            ]
        );

        if ($result['error']) {
            throw new Exception('Ooopps! we could not fetch token from the code');
        }

        return new Token($result['body']);

    }

    public function refreshAccessToken(string $refresh_token) : Token
    {
        $grant = 'refresh_token';
        $method = 'POST';
        $endpoint = '/connect/token';

        $result =  $this->request->makeRequest(
            $endpoint,
            $method,
            [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'grant_type' => $grant,
                    'client_id' => $this->credentials->getClientId(),
                    'client_secret' => $this->credentials->getClientSecret(),
                    'refresh_token' => $refresh_token
                ]
            ]
        );

        if ($result['error']) {
            throw new Exception('Ooopps! we could not fetch token from the code');
        }

        return new Token($result['body']);

    }

    public function revokeAccessToken($access_token) : Token
    {
        $method = 'DELETE';
        $endpoint = '/api/delete';

        return $this->request->makeRequest($endpoint, $method);
    }

    public function providers() : array
    {
        $method = 'GET';
        $endpoint = '/api/providers';

        return $this->request->makeRequest($endpoint, $method);
    }
}