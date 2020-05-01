<?php

namespace Niceperson\Truelayer;

use Exception;

class Authorization
{
    private $request;
    private $credentials;

    public function __construct(Request $request, Credentials $credentials)
    {
        $this->request = $request;
        $this->credentials = $credentials;
    }

    public function getAccessToken($code) : array
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

        if ( $result instanceof Exception ) {
            throw $result;
            throw new Exception("Sorry, we could not fetch a token from that code.");
        }

        return $result['body'];
    }

    public function refreshAccessToken($refresh_token) : array
    {
        $grant = 'refresh_token';
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
                    'refresh_token' => $refresh_token
                ]
            ]
        );

        if ( $result instanceof Exception ) {
            throw new Exception("Sorry, we could not fetch a token from that code.");
        }

        return $result['body'];
    }
}
