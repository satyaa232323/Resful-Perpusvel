<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Tampan',
                'email' => 'admin@gmail.com',
                'avatar' => 'https://ui-avatars.com/api/?name=Admin+Tampan&background=random',
                'gender' => 'male',
                'password' => bcrypt('admin123'),
                'role_id' => 1, 
            ],

             [
                'name' => 'User Cantik',
                'email' => 'user@gmail.com',
                'avatar' => 'https://ui-avatars.com/api/?name=Admin+Tampan&background=random',
                'gender' => 'female',
                'password' => bcrypt(value: 'user123'),
                'role_id' => 1, 
            ],
            
        ];

        $users = DB::table('users')->insert($users);
    }
    

}