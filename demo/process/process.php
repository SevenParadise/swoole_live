<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/30
 * Time: 10:40 PM
 */
$process = new Swoole\Process(function ($pro){

}, true);

$pid = $process->start();

echo $pid;