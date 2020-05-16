<?php

namespace Niceperson\Truelayer;

class Credentials
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $scopes;
    private $providers;

    public function __construct(string $client_id, string $client_secret, string $redirect_uri, bool $sandbox = false)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;

        $this->scopes = [
            'info',
            'accounts',
            'balance',
            'cards',
            'transactions',
            'direct_debits',
            'standing_orders',
            'offline_access'
        ];

        $this->providers = [
            'uk-ob-all',
            'uk-oauth-all'
        ];

        if ($sandbox) {
            array_push($this->providers, 'uk-cs-mock');
        }
    }

    public function getProviders() : string
    {
        return implode('%20', $this->providers);
    }

    public function getScope() : string
    {
        return implode('%20', $this->scopes);
    }

    public function getClientId() : string
    {
        return $this->client_id;
    }

    public function getClientSecret() : string
    {
        return $this->client_secret;
    }

    public function getRedirectUri() : string
    {
        return $this->redirect_uri;
    }
}
