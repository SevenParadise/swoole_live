<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/2/1
 * Time: 9:41 PM
 */
namespace app\common\task;

use app\common\Redis;
use app\common\RedisInstance;

class Task {

    public function sendSms($data, $serv) {
        $redis = (new RedisInstance())->get();
        $redis->set(Redis::smsKey($data['mobile']), $data['code'], 120);
        $redis->close();
        return "on task finish"; // 告诉worker
    }

    public function pushLive($data, $serv) {
        $redis = (new RedisInstance())->get();
        $fds = $redis->sMembers("game_live");
        foreach ($fds as $fd){
            $serv->push($fd, json_encode($data));
        }
        $redis->close();
    }
}