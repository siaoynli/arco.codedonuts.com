<?php
//等同
//Broadcast::channel('User.{id}', function ($user, $id) {
//    \Illuminate\Support\Facades\Log::info("user:", $user->toArray());
//    \Illuminate\Support\Facades\Log::info("id:", ['id'=>$id]);
//    return $user->id == $id;
//});
namespace App\Broadcasting;

use App\Models\Api\V1\User;

class UserPrivateChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @param $id
     * @return array|bool
     */
    public function join(User $user, $id): bool|array
    {
        return $user->id == $id;
    }
}
