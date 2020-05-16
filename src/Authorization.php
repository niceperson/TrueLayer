<?php

namespace Niceperson\Truelayer;

use Exception;
use Niceperson\Truelayer\Token;

class Authorization
{
    protected $request;
    protected $credentials;
    protected $domain;

    public function __construct(Request $request, Credentials $credentials, bool $sandbox = false)
    {
        $this->domain = $sandbox ? 'https://auth.truelayer-sandbox.com' : 'https://auth.truelayer.com';
        $this->request = $request;
        $this->credentials = $credentials;
    }

    public function getAuthLink(string $format = '') : string
    {
        if (empty($format)) {
            $format = "%s/?response_type=code&client_id=%s&scope=%s&redirect_uri=%s&response_mode=form_post&providers=%s";
        }

        $auth_link = sprintf(
            $format,
            $this->domain,
            $this->credentials->getClientId(),
            $this->credentials->getScope(),
            $this->credentials->getRedirectUri(),
            $this->credentials->getProviders()
        );

        return $auth_link;
    }

    public function getAccessToken(string $code) : Token
    {
        $base = $this->domain;
        $grant = 'authorization_code';
        $method = 'POST';
        $endpoint = '/connect/token';

        $result = $this->request->makeRequest(
            $base,
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
        $base = $this->domain;
        $grant = 'refresh_token';
        $method = 'POST';
        $endpoint = '/connect/token';

        $result =  $this->request->makeRequest(
            $base,
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

    public function revokeAccessToken(string $access_token) : array
    {
        $base = $this->domain;
        $method = 'DELETE';
        $endpoint = '/api/delete';

        return $this->request->makeRequest(
            $base,
            $endpoint,
            $method,
            [
                'headers' => ['Authorization' => sprintf('Bearer %s', $access_token)]
            ]
        );
    }

    public function providers() : array
    {
        $base = $this->domain;
        $method = 'GET';
        $endpoint = '/api/providers';

        return $this->request->makeRequest($base, $endpoint, $method);
    }
}