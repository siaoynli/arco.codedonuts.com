<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->comment('标题');
            $table->string('slug', 255)->nullable()->comment('别名');
            $table->unsignedInteger('category_id')->default(0)->index('category_id')->comment('所属栏目');
            $table->unsignedInteger('topic_id')->default(0)->comment('所属专题');
            $table->string('attributes', 100)->default("normal")->index('attribute')->comment('属性');
            $table->string('external_link', 100)->nullable()->comment('外部链接');
            $table->string('keywords', 255)->nullable()->comment('关键词');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->text('summary')->nullable()->comment('摘要');
            $table->json('thumb_files')->nullable()->comment('缩略图');
            $table->longText('content')->nullable()->comment('正文');
            $table->string('tags', 255)->nullable()->comment('标签');
            $table->string('author', 255)->nullable()->comment('作者');
            $table->string('editor', 50)->nullable()->comment('编辑');
            $table->string('source', 50)->nullable()->comment('来源');
            $table->string('source_url', 255)->nullable()->comment('来源链接');
            $table->unsignedInteger('click')->default(0)->comment('点击数');
            $table->boolean('allow_comment')->default(0)->comment('开放评论');
            $table->unsignedInteger('sort')->default(1)->index('sort')->comment('排序');
            $table->unsignedInteger('user_id')->default(0)->comment('添加用户id');
            $table->timestamp('published_at')->nullable()->comment('发布日期');
            $table->tinyInteger('status')->default(0)->comment('状态,-1冻结 0普通，1通过');
            $table->string('recommend_ids', 255)->nullable()->default('')->comment("推荐文章");
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('article_attaches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('article_id')->comment('文章标题');
            $table->integer('attach_type')->default(0)->comment('0普通文件,1视频');
            $table->string('attach_name')->nullable()->comment('附件名称');
            $table->string('attach_file')->nullable()->comment('附件路径');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_attaches');
    }
};
