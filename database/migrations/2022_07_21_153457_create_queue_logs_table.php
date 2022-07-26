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
        Schema::create('queue_logs', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable()->comment("内容");
            $table->string('class_name')->nullable()->comment("队列类名");
            $table->boolean('status')->default(0)->comment("状态");
            $table->string('response')->nullable()->comment("请求结果");
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
        Schema::dropIfExists('queue_logs');
    }
};
