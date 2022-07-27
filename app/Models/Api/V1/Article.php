<?php

namespace App\Models\Api\V1;

use App\Models\Api\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Article extends Model
{
    use HasFactory;


    protected $table = "articles";

    protected $fillable = [
        'title',
        'slug',
        'column_id',
        'topic_id',
        'attributes',
        'external_link',
        'keywords',
        'description',
        'summary',
        'thumb_files',
        'content',
        'tags',
        'click',
        'sort',
        'user_id',
        'author',
        'editor',
        'source',
        'source_url',
        'allow_comment',
        'recommend_ids',
        'published_at',
        'status',
        'user_id',
    ];


    protected $casts = [
        'thumb_files' => 'array'
    ];


    protected $hidden = [
        "status",
        "recommend_ids",
        "allow_comment",
        "user_id",
    ];

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/26 17:19
     * @Description: 保存数据时,处理slug字段
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }
}
