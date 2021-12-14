<?php

use Illuminate\Database\Seeder;

class AdminUser extends Seeder
{
    /**
     * Create admin user.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret')
        ]);
    }
}
