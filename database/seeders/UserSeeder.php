<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB, Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userArray = [
            [
                'name' => 'Ankur',
                'email' => 'ankur@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ashish',
                'email' => 'ashish@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Madhur',
                'email' => 'madhur@gmail.com',
                'password' => Hash::make('password'),
            ],
        ];

        DB::table('users')->insert($userArray);
    }
}
