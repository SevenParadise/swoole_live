<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/30
 * Time: 4:29 PM
 */
$client = new swoole_client(SWOOLE_SOCK_TCP);

if (!$client->connect('127.0.0.1', 9501, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}

$client->send("hello world\n");
echo $client->recv();
$client->close();