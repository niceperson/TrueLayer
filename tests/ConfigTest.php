<?php

namespace Niceperson\Truelayer\Tests;

use PHPUnit\Framework\TestCase;
use Niceperson\Truelayer\Config;

class ConfigTest extends TestCase
{
    public function testConfigsHasProperties()
    {
        $config = new Config('client_id', 'client_secret', 'redirect_uri');
        $this->assertTrue(method_exists($config, 'getAuthPath'));
        $this->assertTrue(method_exists($config, 'getDataPath'));
        $this->assertTrue(method_exists($config, 'getClientId'));
        $this->assertTrue(method_exists($config, 'getClientSecret'));
        $this->assertTrue(method_exists($config, 'getRedirectUri'));
        $this->assertTrue(method_exists($config, 'getIsSandbox'));
    }

    public function testConfigPropertyValues()
    {
        // production environment
        $config = new Config('client_id', 'client_secret', 'redirect_uri');
        $this->assertEquals($config->getIsSandbox(), false);
        $this->assertEquals($config->getAuthPath(), 'https://auth.truelayer.com');
        $this->assertEquals($config->getDataPath(), 'https://api.truelayer.com/data/v1');
        $this->assertEquals($config->getClientId(), 'client_id');
        $this->assertEquals($config->getClientSecret(), 'client_secret');
        $this->assertEquals($config->getRedirectUri(), 'redirect_uri');

        // sandbox environment
        $config = new Config('client_id', 'client_secret', 'redirect_uri', true);
        $this->assertEquals($config->getIsSandbox(), true);
        $this->assertEquals($config->getAuthPath(), 'https://auth.truelayer-sandbox.com');
        $this->assertEquals($config->getDataPath(), 'https://api.truelayer-sandbox.com/data/v1');
    }
}