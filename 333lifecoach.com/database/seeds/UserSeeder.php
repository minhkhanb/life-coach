<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456@'),
                'active' => \App\Model\User::ACTIVE_COMMON,
                'type' => \App\Model\User::TYPE_ADMIN,
                'username' => 'admin'
            ]
        ]);
    }
}
