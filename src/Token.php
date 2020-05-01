<?php

namespace Niceperson\TrueLayer;

use DateInterval;
use DateTime;

class Token
{
    protected $access_token;
    protected $expires_in;
    protected $token_type;
    protected $refresh_token;
    protected $scope;
    protected $created_at;

    public function __construct(array $token)
    {
        $this->access_token = $token['access_token'];
        $this->expires_in = $token['expires_in'];
        $this->token_type = $token['token_type'];
        $this->refresh_token = $token['refresh_token'];
        $this->scope = $token['scope'];
        $this->created_at = ( isset($token['created_at']) ) ? new DateTime($token['created_at']) : new DateTime();
    }


    public function getAccessToken() : string
    {
        return $this->access_token;
    }

    public function getRefreshToken() : string
    {
        return $this->refresh_token;
    }

    public function isExpired() : bool
    {
        $interval = new DateInterval("PT" . $this->expires_in . "S");
        $expires = (clone $this->created_at)->add($interval);

        return $expires < (new DateTime);
    }

}
