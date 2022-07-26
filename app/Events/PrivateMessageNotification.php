<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateMessageNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public string $message;
    public string $type;
    public string $title;
    private string $connection = 'rabbitmq';
    private string $queue = 'pusher';
    private int $id;

    public function __construct($id, $message, $title = "", $type = "info")
    {
        $this->id = $id;
        $this->message = $message;
        $this->type = $type;
        $this->title = $title;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/22 15:26
     * @Description: 私有广播 channels.php配置频道权限
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->id);
    }
}
