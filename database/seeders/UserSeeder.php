<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@woodycraft.com'],
            [
                'nom'      => 'Admin',
                'prenom'   => 'Super',
                'role'     => 'admin',
                'telephone'=> '0102030405',
                'password' => Hash::make('password'),
            ]
        );
    }
}
