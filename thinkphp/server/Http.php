<?php

/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/2/1
 * Time: 11:56 AM
 */

class Http {

    CONST HOST = "0.0.0.0";
    CONST PORT = 8080;

    public $server = null;

    public function __construct()
    {
//        $this->server = new Swoole\WebSocket\Server(self::HOST, self::PORT);
        $this->server = new Swoole\Http\Server(self::HOST, self::PORT);
        $this->server->set([
            'worker_num' => 5,
            'task_worker_num' => 4,
            'enable_static_handler' => true,
            'document_root' => '/Users/zhangcs/opt/env/dnmp/sites/swoole/study/thinkphp/public/static',
        ]);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
//        $this->server->on('open', [$this, 'onOpen']);
//        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->start();
    }

    public function onWorkerStart($server, $work_id)
    {
        require __DIR__ . '/../vendor/autoload.php';
    }

    public function onOpen($ws, $request) {
        print_r($request->fd);
    }

    public function onMessage($ws, $frame) {
        echo "ser-push-message:{$frame->data}\n";
    }

    public function onRequest($request, $response) {
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
        $_POST['http_server'] = $this->server;
        $content = (new think\App())->http->run();
        $response->end($content->getContent());
    }

    public function onTask($serv, $taskId, $workerId, $data) {
        // 分发 task 任务机制，
        $task = new \app\common\task\Task();
        $method = $data['method'];
        return $task->$method($data['data']);
    }

    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }

}

$http = new Http();