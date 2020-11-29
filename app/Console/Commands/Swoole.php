<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class Swoole extends Command
{
    // 命令名称
    protected $signature = 'swoole';
    // 命令说明
    protected $description = '这是关于swoole websocket的一个测试demo';
    // swoole websocket服务
    private static $server = null;

    private static $rooms = [];

    private static $connects = [];

    private static $roomNum = 0;


    public function __construct()
    {
        parent::__construct();
    }

    // 入口
    public function handle()
    {
        $this->redis = Redis::connection('websocket'); // 提前在config/database配置好
        $server = self::getWebSocketServer();
        $server->on('open', [$this, 'onOpen']);
        $server->on('message', [$this, 'onMessage']);
        $server->on('close', [$this, 'onClose']);
        $server->on('request', [$this, 'onRequest']);
        $this->line("swoole服务启动成功 ...");
        $server->start();
    }

    // 获取服务
    public static function getWebSocketServer()
    {
        if (!(self::$server instanceof \swoole_websocket_server)) {
            self::setWebSocketServer();
        }
        return self::$server;
    }

    // 服务处始设置
    protected static function setWebSocketServer()
    {
        // self::$server = new \swoole_websocket_server("0.0.0.0", 9502);
        self::$server = new \swoole_websocket_server("0.0.0.0", 10086);
        self::$server->set([
            'worker_num' => 1,
            'heartbeat_check_interval' => 60, // 60秒检测一次
            'heartbeat_idle_time' => 121, // 121秒没活动的
            'daemonize' => 1, // 守护模式
        ]);
    }

    // 打开swoole websocket服务回调代码
    public function onOpen($server, $request)
    {
        if ($this->checkAccess($server, $request)) {
            // self::$server->push($request->fd, "打开swoole服务成功！");

        }
    }

    // 给swoole websocket 发送消息回调代码
    public function onMessage($server, $frame)
    {
        $data = json_decode($frame->data, true);
        if ($data['type'] == 'text') {
            // 检查是第一次进入还是后续发消息
            $check = $this->createOrJoin($frame->fd);
            $this->line('收到消息：' . $data['content']);
            if ($check == 'create') {
                $response = [
                    'ori' => $data['content'],
                    'text' => "欢迎来到聊天室-》" . $data['content'],
                ];
            } else {
                $response = [
                    'ori' => $data['content'],
                    'text' => "收到你的消息啦-》" . $data['content'] . '请稍等',
                ];
            }

            $response = json_encode($response);

            self::$server->push($frame->fd, $response);
            $this->delay(2, $frame);

        }
    }

    public function delay($time, $frame)
    {
        sleep($time);
        $data = json_decode($frame->data, true);
        $this->line('延时：' . $time . $data['content']);
        $response = [
            'ori' => $data['content'],
            'text' => "延时" . $data['content'],
        ];
        $response = json_encode($response);
        self::$server->push($frame->fd, $response);
    }

    public function createOrJoin($id)
    {
        $rooms = array_keys(self::$rooms);
        if (!empty($rooms)) {
            print_r($rooms);
            $room_id = array_shift($rooms);
        } else {
            $room_id = 1;
        }
        if (isset(self::$connects[$id])) {
            return 'join';
        } else {
            // 存储个人信息
            self::$connects[$id] = [
                'room_id' => $room_id,
            ];
            // print_r(self::$rooms);
            // print_r($room_id);
            self::$rooms[$room_id][] = $id;
            return 'create';
        }
    }

    // http请求swoole websocket 回调代码
    public function onRequest($request, $response)
    {
    }

    // websocket 关闭回调代码
    public function onClose($serv, $fd)
    {
        $this->line("客户端 {$fd} 关闭");
        // print_r(self::$connects);
        if (isset(self::$connects[$fd])) {
            $room_id = self::$connects[$fd]['room_id'];
            $key = array_search($fd, self::$rooms[$room_id]);
            unset(self::$rooms[$room_id][$key]);
        }
    }

    // 校验客户端连接的合法性,无效的连接不允许连接
    public function checkAccess($server, $request): bool
    {
        $bRes = true;
        if (!isset($request->get) || !isset($request->get['token'])) {
            self::$server->close($request->fd);
            $this->line("接口验证字段不全");
            $bRes = false;
        } else if ($request->get['token'] !== "123456") {
            $this->line("接口验证错误");
            $bRes = false;
        }
        return $bRes;
    }

    // 启动websocket服务
    public function start()
    {
        self::$server->start();
    }
}