<?php


use App\Broadcasting\ChatRoomChannel;
use App\Broadcasting\UserPrivateChannel;


/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.Models.User.{id}', UserPrivateChannel::class);

Broadcast::channel('Chat.{chatId}', ChatRoomChannel::class);
