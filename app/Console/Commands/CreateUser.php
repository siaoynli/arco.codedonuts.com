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
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建测试用户';

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
                "name"=>"admin",
                "password"=>password_hash("123456",PASSWORD_BCRYPT),
                "email"=>"demo@example.com",
            ]
        );
        $this->info("created successfully!");
    }
}
