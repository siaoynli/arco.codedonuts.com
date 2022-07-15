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
        Schema::table('users', function (Blueprint $table) {

            $table->string('phone', 11)->nullable()->index('phone')->comment('手机号码');
            $table->string('avatar', 100)->nullable()->comment('头像');
            $table->string('nick_name', 50)->nullable()->comment('昵称');
            $table->string('cn_name', 20)->nullable()->comment('真实姓名');
            $table->boolean('gender')->default(0)->comment('性别');

            $table->string('qq', 20)->nullable()->comment('qq号码');
            $table->string('address', 255)->nullable()->comment('地址');
            $table->integer('login_count')->default(0)->comment('登录次数');
            $table->integer('login_error_count')->default(0)->comment('登录错误次数');
            $table->timestamp('login_time')->nullable()->comment('登录时间');
            $table->string('login_ip', 20)->nullable()->comment('登录ip');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->string('remarks', 255)->nullable()->comment('备注');
            $table->integer('role_id')->default(0);
            $table->integer('department_id')->nullable();
            $table->boolean('is_admin')->default(0)->comment('是否是管理员');
            $table->boolean('login_notification')->default(0)->comment('登录账号，是否短信提醒');
            $table->timestamp('phone_verified_at')->nullable()->comment('手机号码验证');
            $table->string('wx_openid', 100)->nullable()->index('wx_openid');
            $table->string('qq_openid', 100)->nullable()->index('qq_openid');
            $table->string('ios_openid', 100)->nullable()->index('apple_id');
            $table->string('device_hash', 100)->nullable()->index('设备id');
            $table->boolean('open_comment')->default(1)->comment('允许评论');
            $table->string('invite_code', 20)->nullable()->comment('邀请码');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropColumn('nick_name');
            $table->dropColumn('cn_name');
            $table->dropColumn('gender');
            $table->dropColumn('qq');
            $table->dropColumn('address');
            $table->dropColumn('login_count');
            $table->dropColumn('login_error_count');
            $table->dropColumn('login_time');
            $table->dropColumn('login_ip');
            $table->dropColumn('status');
            $table->dropColumn('remarks');
            $table->dropColumn('role_id');
            $table->dropColumn('department_id');
            $table->dropColumn('is_admin');
            $table->dropColumn('login_notification');
            $table->dropColumn('phone_verified_at');
            $table->dropColumn('wx_openid');
            $table->dropColumn('qq_openid');
            $table->dropColumn('ios_openid');
            $table->dropColumn('device_hash');
            $table->dropColumn('open_comment');
            $table->dropColumn('invite_code');
            $table->dropSoftDeletes();
        });
    }
};
