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
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment("标题");
            $table->string('type')->default('Article')->comment("类型")->index();
            $table->string('slug', 255)->nullable()->comment('别名');
            $table->string('keywords', 255)->nullable()->comment('关键词');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->string('summary', 255)->nullable()->comment('摘要');
            $table->string('external_link', 100)->nullable()->comment('外部链接');
            $table->json('thumb_files')->nullable()->comment('缩略图');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父级id');
            $table->boolean('is_directory')->default(false);
            $table->unsignedInteger('level')->default(0);
            $table->string('path')->nullable();
            $table->tinyInteger('status')->default(0)->comment('状态, 0不开放，1开放');
            $table->timestamps();

//            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
