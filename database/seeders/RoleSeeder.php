<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::create(['name' => 'full_admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'technician']);
        Role::create(['name' => 'request_maker']);
    }
}
