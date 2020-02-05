<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/2/5
 * Time: 10:07 PM
 */
Swoole\Timer::tick(2000, function (){
    echo microtime() . PHP_EOL;
});