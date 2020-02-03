<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/2/3
 * Time: 11:21 AM
 */
use think\App;
use app\common\RedisInstance;
class Websocket
{
    CONST HOST = "0.0.0.0";
    CONST PORT = 8080;

    public $server = null;

    public function __construct()
    {
        require __DIR__ . '/../app/common/RedisInstance.php';
        // 删除 game_live
        $redis = (new RedisInstance())->get();
        $redis->del("game_live");
        $redis->close();

        $this->server = new Swoole\WebSocket\Server(self::HOST, self::PORT);
//        $this->server = new Swoole\Http\Server(self::HOST, self::PORT);
        $this->server->set([
            'worker_num' => 5,
            'task_worker_num' => 4,
            'enable_static_handler' => true,
            'document_root' => '/Users/zhangcs/opt/env/dnmp/sites/swoole/study/thinkphp/public/static',
        ]);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('close', [$this, 'onClose']);
        $this->server->start();
    }

    public function onWorkerStart($server, $work_id)
    {
        require __DIR__ . '/../vendor/autoload.php';
    }

    public function onOpen($ws, $request) {
        $redis = (new RedisInstance())->get();
        $redis->sAdd("game_live", $request->fd);
        $redis->close();
    }

    public function onMessage($ws, $frame) {
        echo "server-push-message:{$frame->data}\n";
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
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        $_POST['server'] = $this->server;
        $content = (new think\App())->http->run();
        $response->end($content->getContent());
    }

    public function onTask($serv, $taskId, $workerId, $data) {
        // 分发 task 任务机制，
        $task = new \app\common\task\Task();
        $method = $data['method'];
        return $task->$method($data['data'], $serv);
    }

    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }

    public function onClose($serv, $fd){
        // 删除
        $redis = (new RedisInstance())->get();
        $redis->sRem("game_live", $fd);
        $redis->close();
    }
}

$ws = new Websocket();