<?php

namespace Niceperson\Truelayer;

class Config
{
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $is_sandbox;

    public function __construct(string $client_id, string $client_secret, string $redirect_uri, bool $is_sandbox = false)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->is_sandbox = $is_sandbox;
    }

    public function getAuthPath() : string
    {
        return $this->is_sandbox ? 'https://auth.truelayer-sandbox.com' : 'https://auth.truelayer.com';
    }

    public function getPayPath() : string
    {
        return $this->is_sandbox ? 'https://pay-api.truelayer-sandbox.com' : 'https://pay-api.truelayer.com';
    }

    public function getDataPath() : string
    {
        $version = '/data/v1';
        $base = $this->is_sandbox ? 'https://api.truelayer-sandbox.com' : 'https://api.truelayer.com';
        return $base . $version;
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

    public function getIsSandbox() : bool
    {
        return $this->is_sandbox;
    }
}
