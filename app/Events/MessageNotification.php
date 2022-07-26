<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * 默认监听事件名 App\Events\MessageNotification
 */
class MessageNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public string $message;
    public string $type;
    public string $title;
    private string $connection = 'rabbitmq';
    private string $queue = 'pusher';

    public function __construct($message, $title = "", $type = "info")
    {
        $this->message = $message;
        $this->type = $type;
        $this->title = $title;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/22 12:24
     * @Description: 频道
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('Notification');
    }

}
