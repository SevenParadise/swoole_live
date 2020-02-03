<?php
namespace app\controller;

use app\BaseController;
use app\common\RedisInstance;

class Admin extends BaseController
{
    public function image()
    {
        return json_encode([
            'status' => "ok",
            'msg' => 'success',
            'data' => [
                'image' => "http://localhost:8080/upload/d42f70594a619e95c28e27cb80d3c0e6.png"
            ]
        ]);
        $file = request()->file('file');
        $info = $file->move('../public/static/upload');
        if($info) {
            $data = [
//                'image' => "http://localhost:8080/upload/" . $info->getSaveName(),
                'image' => "http://localhost:8080/upload/d42f70594a619e95c28e27cb80d3c0e6.png"
            ];
            return json_encode([
                'status' => "ok",
                'msg' => 'success',
                'data' => [
                    'image' => "http://localhost:8080/upload/d42f70594a619e95c28e27cb80d3c0e6.png"
                ]
            ]);
        }else {
            return json_encode([
                'status' => "error",
                'msg' => 'success',
                'data' => [
                ],
            ]);
        }
    }

    public function push()
    {
        // 获取连接的用户
        $server = $_POST['server'];
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];

        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name']) ?$teams[$_GET['team_id']]['name'] : '直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo']) ?$teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image' => !empty($_GET['image']) ? $_GET['image'] : '',
        ];
        $taskData = [
            'method' => 'pushLive',
            'data' => $data
        ];
        $server->task($taskData);
        return "push";
    }
}
