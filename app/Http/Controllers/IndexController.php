<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Api\V1\Product;
use App\Models\Api\V1\User;


class IndexController extends Controller
{
    public function index()
    {
        $product = Product::find(30);
//        $product->long_title="修改后的标题2";
        $product->update(["long_title" => "update后的标题"]);

        return $product;
        return ping();
    }

    public function user()
    {
        return new UserResource(User::find(1));
    }
}
