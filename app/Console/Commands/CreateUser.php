<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建超级管理员';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create(
            [
                "name" => "admin",
                "password" => password_hash("123456", PASSWORD_BCRYPT),
                "email" => "demo@demo.com",
                "avatar" => "//lf1-xgcdn-tos.pstatp.com/obj/vcloud/vadmin/start.8e0e4855ee346a46ccff8ff3e24db27b.png",
                "cn_name" => "超级管理员",
                "nick_name" => "西瓜哥",
                "phone" => "18906715574",
                "wx_openid" => "oSoYGj5lL0iaG7o-0pNQUo_OpRjE",
                'phone_verified_at' => now(),
                'is_admin' => 1,
            ]
        );
        $this->info("created successfully!");
    }
}
