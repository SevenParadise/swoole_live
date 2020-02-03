<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/30
 * Time: 6:23 PM
 */
$server = new Swoole\WebSocket\Server('0.0.0.0', 8812);

$server->set([
    'enable_static_handler' => true,
    'document_root' => '/Users/zhangcs/opt/env/dnmp/sites/swoole/study/demo/data',
]);


$server->on('open', 'onOpen');

function onOpen($server, $request) {
    print_r($request->fd);
}

$server->on('message', function ($server, $frame){
    echo "receive from {$frame->fd}, {$frame->data}, {$frame->opcode}, fin:{$frame->finish}";
    $server->push($frame->fd, "singwa-push-success");
});

$server->on('close', function($server, $fd){
    echo "client $fd close\n";
});

$server->start();