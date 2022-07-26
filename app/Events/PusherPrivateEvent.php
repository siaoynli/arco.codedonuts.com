<?php

namespace App\Events;

use App\Models\Api\V1\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherPrivateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $connection = 'redis';

    public string $message;

    /**
     * 放置广播作业的队列的名称。
     * @var string
     */
    public string $queue = 'default';

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
     * @Date: 2022/7/21 16:11
     * @Description:  获取事件应该广播的频道。
     * @return array|PrivateChannel
     */
    public function broadcastOn(): array|PrivateChannel
    {
        return new PrivateChannel('user.1');
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:11
     * @Description: 事件的广播名称。如果您使用 broadcastAs 方法自定义广播名称，则应确保使用前导 . 字符注册您的侦听器
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'my-user-event';
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:10
     * @Description: 广播条件
     * @return bool
     */
    public function broadcastWhen(): bool
    {
        return true;
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:09
     * @Description: 放置广播作业的队列的名称。
     * @return string
     */
    public function broadcastQueue(): string
    {
        return 'default';
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/21 16:14
     * @Description: 广播的数据。
     * @return int[]
     */
    public function broadcastWith(): array
    {
        return ["user" => User::find(1)];
    }
}
