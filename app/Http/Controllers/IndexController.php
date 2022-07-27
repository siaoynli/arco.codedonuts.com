<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Api\V1\Article;
use App\Models\Api\V1\User;

class IndexController extends Controller
{
    public function index()
    {
        return ping();
    }

    public function user()
    {
        $article = [
            "title" => "hello world",
            "summary" => "sdfdsf",
            "content" => "Sdfsdf",
            "author" => "Sdfsdf",
            "editor" => "Sdfsdf",

        ];

        Article::create($article);


        return new UserResource(User::find(1));
    }
}
