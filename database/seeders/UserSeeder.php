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
            'city' => 'Varginha',
            'state' => 'MG',
            'latitude' => '-21.54786635815924',
            'longitude' => '-45.45356328979517',
            'first_access' => false
        ]);
    }
}
