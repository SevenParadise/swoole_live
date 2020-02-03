<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/30
 * Time: 10:58 PM
 */

echo "Begin" . date('Y-m-d H:i:s') . PHP_EOL;
$urls = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com',
    'http://baidu.com?search=singwa',
    'http://baidu.com?search=singwa2',
    'http://baidu.com?search=imooc',
];
$workers = [];
for ($i = 0; $i < 6; $i++) {
    $process = new Swoole\Process(function (Swoole\Process $worker) use ($i, $urls){
        // curl
        $content = curlData($urls[$i]);
        echo $content . PHP_EOL;
    }, true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

foreach ($workers as $worker) {
    echo $worker->read();
}

function curlData($url) {
    sleep(1);
    return "$url Data";
}

echo "End" . date('Y-m-d H:i:s') . PHP_EOL;