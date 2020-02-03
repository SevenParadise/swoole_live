<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/30
 * Time: 5:44 PM
 */

$http = new Swoole\Http\Server("0.0.0.0", 8811);

$http->set([
    'enable_static_handler' => true,
    'document_root' => '/Users/zhangcs/opt/env/dnmp/sites/swoole/study/demo/data',
]);

$http->on("request", function($request, $response){
    var_dump($request->get);

    var_dump($request->cookie);
    $response->end(json_encode($request->get));
});

$http->start();