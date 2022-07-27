<?php

use App\Models\Api\V1\Product;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('long_title');
            $table->unsignedBigInteger('category_id')->default(0);
            $table->string('type')->default(Product::TYPE_NORMAL)->index();
            $table->string('keywords')->nullable();
            $table->string('description')->nullable();
            $table->text('summary');
            $table->string('image');
            $table->boolean('on_sale')->default(true);
            $table->float('rating')->default(5);
            $table->unsignedInteger('sold_count')->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('user_id')->default(0)->comment('添加用户id');
            $table->timestamp('published_at')->nullable()->comment('发布日期');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
