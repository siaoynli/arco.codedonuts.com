<?php

namespace App\Http\Controllers;

use App\Events\ChatRoomEvent;
use App\Events\MessageNotification;
use App\Events\PrivateMessageNotification;
use App\Models\Api\V1\User;
use App\Notifications\InvoicePaid;

class BroadCastsController extends Controller
{
    public function event()
    {
        MessageNotification::dispatch("你有一条新的工作待完成" . time(), '提示信息', 'success');
        return "公共广播:" . time();
    }

    public function priEvent($id)
    {
        PrivateMessageNotification::dispatch($id, "用户" . $id . "你有一条新的工作待完成!" . time(), '提示信息', 'success');
        return "私有广播:" . time();
    }

    public function chat($rid)
    {
        $uid = request()->get("uid", 1);
        ChatRoomEvent::dispatch($rid, "聊天室:" . $rid . ",来自" . $uid . ":你好!" . time());
        return "聊天室广播:" . time();
    }

    public function notification($id)
    {
        $user = User::find($id);
        $user->notify(new InvoicePaid("用户" . $id . "您的订单已经支付!"));
        return "订单通知广播:" . time();
    }

}
