<?php

namespace App\Http\Controllers\Api\V1;


use App\Events\ChatRoomEvent;
use App\Http\Controllers\Controller;

class BroadCastsController extends Controller
{


    public function chat()
    {
        $user = request()->user();
        $roomId = 1;
        broadcast(new ChatRoomEvent($roomId, "来自" . $user->id . ":你好!" . time()))->toOthers();
        return "聊天室广播:" . time();
    }


}
