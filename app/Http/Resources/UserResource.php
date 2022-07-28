<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;

class UserResource extends ApiResource
{

    public function toArray($request): array
    {
        $arr["phone"] = Str::mask($this->resource->phone, '*', 3, 5);
        return array_merge(parent::toArray($request), $arr);
    }
}
