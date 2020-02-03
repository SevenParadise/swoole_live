<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/31
 * Time: 10:28 AM
 */
$http = new Swoole\Http\Server('0.0.0.0', 8011);

$http->set([
    'enable_static_handler' => true,
    'document_root' => '/Users/zhangcs/opt/env/dnmp/sites/swoole/study/server/static',
]);

$http->on('request', function ($request, $response){
    $response->end('hha');
});

$http->start();