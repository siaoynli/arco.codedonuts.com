<?php

namespace App\Console\Commands\Dev;

use App\Models\Api\V1\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arco:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建管理员用户';


    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        User::create(
            [
                "name" => "admin",
                "password" => password_hash("123456", PASSWORD_BCRYPT),
                "email" => "admin@demo.com",
                "avatar" => "//lf1-xgcdn-tos.pstatp.com/obj/vcloud/vadmin/start.8e0e4855ee346a46ccff8ff3e24db27b.png",
                "cn_name" => "超级管理员",
                "nick_name" => "西瓜哥",
                "phone" => "18906715574",
                "wx_openid" => "oSoYGj5lL0iaG7o-0pNQUo_OpRjE",
                'phone_verified_at' => now(),
                'is_admin' => 1,
            ]

        );
        User::create([
            "name" => "user",
            "password" => password_hash("123456", PASSWORD_BCRYPT),
            "email" => "user@demo.com",
            "avatar" => "//lf1-xgcdn-tos.pstatp.com/obj/vcloud/vadmin/start.8e0e4855ee346a46ccff8ff3e24db27b.png",
            "cn_name" => "小明",
            "nick_name" => "小明",
            "phone" => "13516872342",
            "wx_openid" => "oSoYGj5lL0iaG7o-0pNQUo_OpRjE",
            'phone_verified_at' => now(),
            'is_admin' => 1,
        ]);
        $this->info("created successfully!");
    }
}
