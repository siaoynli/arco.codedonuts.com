<?php

namespace Database\Seeders;

use App\Models\Api\V1\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::factory()
            ->count(10)
            ->create()->map(function ($user) {
                if ($user->id == 1) {
                    $user->name = "admin";
                    $user->email = "admin@demo.com";
                    $user->phone = "18906715574";
                    $user->is_admin = 1;
                    $user->wx_openid = 'oSoYGj5lL0iaG7o-0pNQUo_OpRjE';
                    $user->save();
                }
            });


    }
}
