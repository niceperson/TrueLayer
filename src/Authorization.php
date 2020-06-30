<?php

namespace Niceperson\Truelayer;

use \Exception;
use Niceperson\Truelayer\Request;
use Niceperson\Truelayer\Token;
use Niceperson\Truelayer\Config;

class Authorization extends Request
{
    protected $config;

    /**
     * Class constructor - set config from parent
     *
     * @param array  $option guzzle-client options
     *
     * @param object $config truelayer config
     */
    public function __construct(array $option, Config $config)
    {
        parent::__construct($option);
        $this->config = $config;
    }

    /**
     * Generates auth link
     *
     * @return string
     */
    public function generateAuthUrl() : string
    {
        $format = "%s/?response_type=code&client_id=%s&redirect_uri=%s&response_mode=form_post&scope=%s&providers=%s";

        $auth_link = sprintf(
            $format,
            $this->config->getAuthPath(),
            $this->config->getClientId(),
            $this->config->getRedirectUri(),
            $this->getInitAllScopes(),
            $this->getProviders()
        );

        return $auth_link;
    }

    /**
     * Request token from code
     *
     * @param string $code code from truelayer
     *
     * @return object
     */
    public function requestOauthToken(string $code) : Token
    {
        $grant = 'authorization_code';
        $method = 'POST';
        $endpoint = $this->config->getAuthPath() . '/connect/token';
        $data = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => $grant,
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'redirect_uri' => $this->config->getRedirectUri(),
                'code' => $code
            ]
        ];

        $result = $this->makeRequest($endpoint, $method, $data);

        return new Token($result['body']);
    }

    /**
     * Request truelayer revoke access
     *
     * @param string $access_token access_token to be revoked
     *
     * @return array
     */
    public function requestRevokeToken(string $access_token) : array
    {
        $method = 'DELETE';
        $endpoint = $this->config->getAuthPath() . '/api/delete';
        $data = [
            'headers' => ['Authorization' => sprintf('Bearer %s', $access_token)]
        ];

        return $this->makeRequest($endpoint, $method, $data);
    }

    /**
     * Generates providers string
     *
     * @return string
     */
    public function getProviders() : string
    {
        $providers = ['uk-ob-all', 'uk-oauth-all'];

        if ($this->config->getIsSandbox()) {
            array_push($providers, 'uk-cs-mock');
        }

        return implode('%20', $providers);
    }

    /**
     * Generates scopes string
     *
     * @return string
     */
    public function getInitAllScopes() : string
    {
        $scopes = [
            'info',
            'accounts',
            'balance',
            'cards',
            'transactions',
            'direct_debits',
            'standing_orders',
            'offline_access'
        ];

        return implode('%20', $scopes);
    }
}
