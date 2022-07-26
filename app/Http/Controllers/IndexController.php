<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Api\V1\User;

class IndexController extends Controller
{
    public function index()
    {

        return new UserResource(User::find(1));
        return ping();
    }
}
