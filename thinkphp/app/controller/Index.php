<?php
namespace app\controller;

use app\BaseController;
use app\common\Redis;
use app\common\RedisInstance;

class Index extends BaseController
{
    public function index()
    {
        return phpinfo();
        //return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) 2020新春快乐</h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function sms($mobile = "18801773338") {
        $code = rand(1000, 9999);
        $http = $_POST['http_server'];
        $http->task([
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
