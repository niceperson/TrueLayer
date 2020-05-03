<?php

namespace Niceperson\Truelayer;

use Exception;
use Niceperson\Truelayer\Request;

class Data
{
    const DATA_URI = '/data/v1';

    private $request;
    private $token;
    private $actions;

    public function __construct(Request $request, Token $token)
    {
        $this->request = $request;
        $this->token = $token->getAccessToken();
        $this->actions = [
            'META_ME' => self::DATA_URI . '/me',
            'META_INFO' => self::DATA_URI . '/info',
            'ACCT_LIST' => self::DATA_URI . '/accounts',
            'ACCT_VIEW' => self::DATA_URI . '/accounts/%s',
            'ACCT_BALANCE' => self::DATA_URI . '/accounts/%s/balance',
            'ACCT_TRANSACTIONS' => self::DATA_URI . '/accounts/%s/transactions',
            'ACCT_TRANSACTIONS_PENDING' => self::DATA_URI . '/accounts/%s/transactions/pending',
            'ACCT_DIRECT_DEBITS' => self::DATA_URI . '/accounts/%s/direct_debits',
            'ACCT_STANDING_ORDER' => self::DATA_URI . '/accounts/%s/standing_orders',
            'CARD_LIST' => self::DATA_URI . '/cards',
            'CARD_VIEW' => self::DATA_URI . '/cards/%s',
            'CARD_BALANCE' => self::DATA_URI . '/cards/%s/balance',
            'CARD_TRANSACTIONS' => self::DATA_URI . '/cards/%s/transactions',
            'CARD_TRANSACTIONS_PENDING' => self::DATA_URI . '/cards/%s/transactions/pending',
        ];
    }

    // Helper function
    private function getAuthHeader() : array
    {
        return [
            'Authorization' => sprintf('Bearer %s', $this->token)
        ];
    }

    // Helper function
    private function getJsonHeader() : array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    // Helper function
    private function getEndpoint(string $action, $options) : string
    {

        if (empty($options)) {
            $endpoint = $this->actions[$action];
        } elseif (sizeof($options) === 3 ) {
            // both from and to must present else range willl be omitted
            $endpoint = sprintf($this->actions[$action] . '?from=$s&to=$s', ...$options);
        } else {
            // only supply the account_id
            $endpoint =  sprintf($this->actions[$action], ...$options);
        }

        return $endpoint;
    }

    // Perfrom request
    public function fetch(string $action, ...$options) : array
    {
        $method = 'GET';
        $endpoint = $this->getEndpoint($action, $options);
        $data = [
            'headers' => $this->getAuthHeader()
        ];

        $result = $this->request->makeRequest($endpoint, $method, $data);

        if ($result['error']) {
            throw new Exception($result['message']);
        }

        return (sizeof($result['body']['results']) === 1) ? current($result['body']['results']) : $result['body']['results'];
    }
}
