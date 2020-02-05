<?php
namespace app\controller;

use app\BaseController;
use app\common\Redis;
use app\common\RedisInstance;

class Index extends BaseController
{
    public function index()
    {
        return "index";
    }

    public function sms($mobile = "18801773338") {
        $code = rand(1000, 9999);
        $server = $_POST['server'];
        $server->task([
            'method' => 'sendSms',
            'data' => [
                'mobile' => $mobile,
                'code' => $code,
            ],
        ]);
        return json_encode([
            'status' => "ok",
            'msg' => 'success',
            'data' => [
                'mobile' => $mobile,
                'code' => $code
            ],
        ]);
    }

    public function login()
    {
        $mobile = $_GET['phone_num'];
        $code = $_GET['code'];
        $redis = (new RedisInstance())->get();
        if ($redis->get(Redis::smsKey($mobile)) != $code) {
            return json_encode([
                'status' => "error",
                'msg' => 'success',
                'data' => [
                ],
            ]);
        } else {
            $redis->set(Redis::userKey($mobile), json_encode([
                'user' => $mobile,
                'srcKey' => md5(Redis::userKey($mobile)),
                'time' => time(),
                'isLogin' => true,
            ]));
            return json_encode([
                'status' => "ok",
                'msg' => 'success',
                'data' => [
                    'mobile' => $mobile,
                ],
            ]);
        }
    }

}
