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

        $user->destroySanctumTokens($device_name);

        return $user->getSanctumToken($device_name);
    }

    public function logout(Request $request)
    {
        return $request->user()->destroyCurrentSanctumToken();
    }

    public function current(Request $request)
    {
        return $request->user();
    }
}
