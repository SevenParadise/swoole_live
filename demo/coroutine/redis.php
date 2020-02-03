<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/31
 * Time: 9:56 AM
 */

$http = new Swoole\Http\Server('0.0.0.0', 8011);

$http->on('request', function ($request, $response){
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $response->end($redis->get("key"));
});

$http->start();

