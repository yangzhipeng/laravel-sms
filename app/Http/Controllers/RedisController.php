<?php

namespace App\Http\Controllers;

class RedisController extends Controller
{
    public function __construct()
    {}

    public function testredis()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        echo "Connection to server sucessfully";
        $arList = $redis->keys("*");
        echo "Stored keys in redis:: ";
        print_r($arList);
    }
}
