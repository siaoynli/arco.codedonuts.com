<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:24
     * @Description: 公共频道
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('channel-name');
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:23
     * @Description:
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'my-message-event';
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:22
     * @Description:广播的数据
     * @return string[]
     */
    public function broadcastWith(): array
    {
        return ["message" => $this->message];
    }
}
