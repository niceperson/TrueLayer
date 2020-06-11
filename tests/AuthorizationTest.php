<?php

namespace Niceperson\Truelayer\Tests;

use PHPUnit\Framework\TestCase;
use Niceperson\Truelayer\Config;
use Niceperson\Truelayer\Authorization;

class AuthorizationTest extends TestCase
{
    public function testAuthorizationsHasProperties()
    {
        $config = new Config('client_id', 'client_secret', 'redirect_uri');
        $auth = new Authorization(['timeout' => 60], $config);

        $this->assertTrue(method_exists($auth, 'generateAuthUrl'));
        $this->assertTrue(method_exists($auth, 'requestOauthToken'));
        $this->assertTrue(method_exists($auth, 'requestRevokeToken'));
        $this->assertTrue(method_exists($auth, 'getProviders'));
        $this->assertTrue(method_exists($auth, 'getScopes'));
    }
}