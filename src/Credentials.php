<?php

namespace Niceperson\Truelayer;

class Credentials
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function __construct(string $client_id, string $client_secret, string $redirect_uri)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
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
