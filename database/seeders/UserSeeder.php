<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' =>  'cristian.aob@gmail.com',
            'password' => Hash::make('123456'),
            'city' => 'Matelândia',
            'state' => 'PR',
            'latitude' => '-25.240922510579942',
            'longitude' => '-53.98312937614596',
            'first_access' => false
        ]);
    }
}
