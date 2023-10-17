<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'user1',
            'username' => 'user1',
            'email' => 'user1',
            'password' => Hash::make('123'),
            'avatar' => 'user1',
            'department_id' => 1,
            'status_id' => 1
        ]);
    }
}