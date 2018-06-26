<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            'full_name' => 'Admin',
            'name'      => 'admin',
            'email'     => 'admin@example.com',
            'password'  => bcrypt('turkcobaltmagazinestash')
        ]);
    }
}
