<?php

namespace App\Models\Api\V1;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    use HasFactory;

    public $timestamps = false;
    // 没有 created_at 和 updated_at 字段
    protected $fillable = [
        'name',
        'value',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
