<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'doaa',
            'email' => 'dkhalf37@gmail.com',
            'password' => bcrypt('doaa123'),
            'user_type'=>'FullAdmin'
        ]);
    }
}
