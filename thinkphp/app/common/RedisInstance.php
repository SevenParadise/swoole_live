<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/2/1
 * Time: 11:34 AM
 */
namespace app\common;

class RedisInstance {

    public function __construct()
    {

    }

    public function get()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        return $redis;
    }
}