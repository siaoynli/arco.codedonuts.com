<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateController extends Controller
{

    private array $devices = ["ios", "android", "pc", "weixin"];


    public function login(Request $request)
    {
        $user = User::where('id', 1)->first();
        $device_name = $request->get("device_name", "pc");
        if (!in_array($device_name, $this->devices)) {
            $device_name = "pc";
        }

        //删除同设备其他token授权
        PersonalAccessToken::where(["tokenable_id" => $user->id, "name" => $device_name])->delete();
        return $user->createToken($device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        //$request->user()->tokens()->delete()
        return $request->user()->tokens()->where("id", getAuthorizationTokenId())->delete();
    }

    public function authenticate(Request $request)
    {
        return $request->user();
    }
}
