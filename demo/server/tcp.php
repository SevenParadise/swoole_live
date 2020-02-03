<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/30
 * Time: 4:03 PM
 */
$serv = new swoole_server("127.0.0.1", 9501);

$serv->set([
    'worker_num' => 4,
    'max_request' => 10000,
]);

$serv->on('connect', function ($serv, $fd, $reactor_id){
    echo "Client: {$reactor_id} - {$fd} - Connect.\n";
});

$serv->on('receive', function($serv, $fd, $reactor_id, $data){
   $serv->send($fd, "Server - {$reactor_id} - ${fd}:" . $data);
});

$serv->on('Close', function($serv, $fd){
    echo "Client: Close.\n";
});

$serv->start();