<?php

namespace Niceperson\Truelayer;

use Exception;
use Niceperson\Truelayer\Request;
use Niceperson\Truelayer\Token;
use Niceperson\Truelayer\Config;

class Data extends Request
{
    protected $config;
    protected $token;
    protected $actions = [
        'META_ME' => '/me',
        'META_INFO' => '/info',
        'ACCOUNT_LIST' => '/accounts',
        'ACCOUNT_VIEW' => '/accounts/%s',
        'ACCOUNT_BALANCE' => '/accounts/%s/balance',
        'ACCOUNT_TRANSACTIONS' => '/accounts/%s/transactions',
        'ACCOUNT_TRANSACTIONS_PENDING' => '/accounts/%s/transactions/pending',
        'ACCOUNT_DIRECT_DEBITS' => '/accounts/%s/direct_debits',
        'ACCOUNT_STANDING_ORDER' => '/accounts/%s/standing_orders',
        'CARD_LIST' => '/cards',
        'CARD_VIEW' => '/cards/%s',
        'CARD_BALANCE' => '/cards/%s/balance',
        'CARD_TRANSACTIONS' => '/cards/%s/transactions',
        'CARD_TRANSACTIONS_PENDING' => '/cards/%s/transactions/pending',
    ];

    /**
     * Class constructor - set config from parent
     *
     * @param array  $option guzzle-client options
     *
     * @param object $config truelayer config
     */
    public function __construct(array $option, Config $config, Token $token = null)
    {
        parent::__construct($option);
        $this->config = $config;
        $this->token = $token;
    }

    /**
     * Helper function
     *
     * @return array
     */
    public function getAuthHeader() : array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $this->token->getAccessToken())
        ];
    }

    /**
     * Helper function
     *
     * @param Token @token - token object
     *
     * @return object
     */
    public function setToken(Token $token) : object
    {
        $this->token = $this->validateToken($token);
        return $this;
    }

    /**
     * Helper function
     *
     * @return string
     */
    public function getEndpoint(string $action, string $account_id) : string
    {
        return empty($account_id) ? $this->actions[$action] : sprintf($this->actions[$action], $account_id);
    }

    /**
     * Perform request
     *
     * @param string $action action preset index
     * @param array  $option account_id / from / to
     */
    public function fetch(string $action, array $options = []) : array
    {
        $account_id = isset($options['account_id']) ? $options['account_id'] : '';
        $query = array_filter([
            'from' => isset($options['from']) ? $options['from'] : null,
            'to' => isset($options['to']) ? $options['to'] : null,
        ]);

        $method = 'GET';
        $endpoint = $this->getEndpoint($action, $account_id);
        $data['headers'] = $this->getAuthHeader();
        $data['query'] = $query;

        $result = $this->makeRequest($endpoint, $method, $data);

        if ($result['error']) {
            throw new Exception($result['reason']);
        }

        return (sizeof($result['body']['results']) === 1) ? current($result['body']['results']) : $result['body']['results'];
    }

    /**
     * Validate token existence and handle expired token
     */
    public function validateToken(Token $token) : Token
    {
        if (is_null($token)) {
            throw new Exception('Token is missing');
        }

        if ($token->isExpired()) {
            if (!$token->isRefreshable()) {
                throw new Exception('Sorry token is expired and could not be refreshed');
            }

            return $this->refreshToken($token);
        }
    }

    /**
     * Perform token refresh
     */
    public function refreshToken(Token $token) : Token
    {
        $grant = 'refresh_token';
        $method = 'POST';
        $endpoint = $this->config->getAuthPath() . '/connect/token';
        $data = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'grant_type' => $grant,
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'refresh_token' => $token->getRefreshToken()
            ]
        ];

        $result =  $this->makeRequest($endpoint, $method, $data);

        if ($result['error']) {
            throw new Exception('Refreshing token failed');
        }

        return new Token($result['body']);
    }
}
