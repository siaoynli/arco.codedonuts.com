<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Api\V1\User;


class IndexController extends Controller
{
    public function index()
    {
        return ping();
    }

    public function user()
    {
        return new UserResource(User::find(1));
    }
}
