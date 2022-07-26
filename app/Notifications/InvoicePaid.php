<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;


class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;


    public string $message;

    public function __construct($message)
    {
        $this->message = $message;
        //自定义队列
        $this->queue = "pusher";
        $this->connection = "rabbitmq";
    }


//    /**
//     * @Author: lixiaoyun
//     * @Email: 120235331@qq.com
//     * @Date: 2022/7/25 14:23
//     * @Description: 指定队列
//     * @return string[]
//     */
//    #[ArrayShape(['mail' => "string", 'broadcast' => "string"])] public function viaQueues(): array
//    {
//        return [
//            'mail' => 'mail-queue',
//            'broadcast' => 'pusher',
//        ];
//    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/25 13:54
     * @Description: 通知使用广播系统
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['broadcast'];
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/25 14:00
     * @Description:
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => $this->message,
        ]);
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/25 14:11
     * @Description: $notifiable=$user
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return $notifiable->toArray();
    }
}
