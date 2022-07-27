<?php

namespace App\Models\Api\V1;


use App\Jobs\SyncOneProductToES;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;


class Product extends Model
{
    use HasFactory;

    const TYPE_NORMAL = 'normal';


    protected $fillable = [
        'title',
        'long_title',
        'description',
        'summary',
        'image',
        'on_sale',
        'rating',
        'sold_count',
        'review_count',
        'price',
        'type',
    ];
    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 16:46
     * @Description: 监听增删改查，同步数据到es
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::created(function ($model) {
            dispatch(new SyncOneProductToES($model));
        });
        static::saved(function ($model) {
            dispatch(new SyncOneProductToES($model));
        });
        static::updated(function ($model) {
            dispatch(new SyncOneProductToES($model));
        });
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 16:48
     * @Description: 商品SKU
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 16:48
     * @Description: 商品属性
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 16:48
     * @Description: 商品类别
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 15:18
     * @Description: 处理成ES的数据
     * @return array
     */
    public function toESArray(): array
    {
        // 只取出需要的字段
        $arr = Arr::only($this->toArray(), [
            'id',
            'type',
            'title',
            'category_id',
            'long_title',
            'on_sale',
            'rating',
            'sold_count',
            'review_count',
            'price',
        ]);


        // 如果商品有类目，则 category 字段为类目名数组，否则为空字符串
        $arr['category'] = $this->category ? [$this->category->name] : '';

        // 类目的 path 字段
        $arr['category_path'] = $this->category ? $this->category->path : '';
        // strip_tags 函数可以将 html 标签去除
        $arr['description'] = strip_tags($this->description);
        $arr['summary'] = strip_tags($this->summary);
        // 只取出需要的 SKU 字段
        $arr['skus'] = $this->skus->map(function (ProductSku $sku) {
            return Arr::only($sku->toArray(), ['title', 'description', 'price']);
        });

        $arr['properties'] = $this->properties->map(function (ProductProperty $property) {
            // 对应地增加一个 search_value 字段，用符号 : 将属性名和属性值拼接起来
            return array_merge(Arr::only($property->toArray(), ['name', 'value']), [
                'search_value' => $property->name . ':' . $property->value,
            ]);
        });

        return $arr;
    }


}
