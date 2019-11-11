<?php

namespace Vandar;

use Vandar\Driver\DriverInterface;
use Vandar\Driver\RestDriver;

class Vandar
{
    private $redirectUrl = "https://vandar.io/ipg/2step/";
    private $api;
    private $driver;
    private $token;

    function __construct($api, DriverInterface $driver = null)
    {
        if (is_null($driver)) {
            $this->driver = new RestDriver();
        }
        $this->driver = $driver;
        $this->api = $api;
    }

    public function request($amount, $mobile = null, $factorNumber = null, $description = null, $callback)
    {
        $inputs = [
            'api_key' => $this->api,
            'amount' => $amount,
            'callback_url' => $callback,
            'mobile_number' => $mobile,
            'factorNumber' => $factorNumber,
            'description' => $description,
        ];
        $result = $this->driver->request($inputs);
        if (isset($result['token'])) {
            $this->token = $result['token'];
        }
        return $result;
    }

    public function verify($token)
    {
        return $this->driver->verify($token, $this->api);
    }
    
    public function transaction($token)
    {
        return $this->driver->verify($token, $this->api);
    }
    
    public function confirm($token)
    {
        return $this->driver->confirm($token, $this->api);
    }

    public function redirect()
    {
        header('Location: ' . $this->redirectUrl());
        die();
    }

    public function redirectUrl()
    {
        return $this->redirectUrl . $this->token;
    }

    public function enableTest()
    {
        $this->redirectUrl .= "test/";
        $this->driver->enableTest();
    }
}
