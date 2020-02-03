<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/31
 * Time: 10:28 AM
 */
$server = new Swoole\Http\Server('0.0.0.0', 8080);

$server->set([
    'enable_static_handler' => true,
    'document_root' => '/Users/zhangcs/opt/env/dnmp/sites/swoole/study/thinkphpbak/public/static',
    'worker_num' => 5,
//    'task_worker_num' => 2
]);

$server->on('WorkerStart', function ($server, $work_id){
    require __DIR__ . '/../vendor/autoload.php';
});

$server->on('request', function ($request, $response) use ($server){
    $_SERVER = [];
    if (isset($request->server)) {
        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    if (isset($request->header)) {
        foreach ($request->header as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }
    $_GET = [];
    if (isset($request->get)) {
        foreach ($request->get as $k => $v) {
            $_GET[$k] = $v;
        }
    }
    $_POST= [];
    if (isset($request->post)) {
        foreach ($request->server as $k => $v) {
            $_POST[$k] = $v;
        }
    }
//    $http->task([
//        'haha' => 1,
//    ]);
    $content = (new think\App())->http->run();
    $response->end($content->getContent());
//    $http->close();
//    ob_start();
//    try{
//        // 执行HTTP应用并响应
//        (new think\App())->http->run()->getContent();
//    }catch (\Exception $e) {
//        echo 111;
//        $response->end($e->getMessage());
//    }
//    $content = ob_get_contents();
//    ob_end_clean();
});

//$http->on('task', function ($serv, $taskId, $workerId, $data){
//    print_r($data);
//    return 'task_success';
//});
//
//$http->on('finish', function($serv, $taskId, $data){
//    echo "taskId:{$taskId}\n";
//    echo "finish-data-sucess:{$data}\n";
//});

$server->start();