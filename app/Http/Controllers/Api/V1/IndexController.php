<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return "api v1 works:" . date("Y-m-d");
    }
}
