<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin PMI',
            'email' => 'admin@pmi.org',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Ahmad Fadli',
            'email' => 'ahmad@pmi.org',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@pmi.org',
            'password' => Hash::make('password'),
        ]);
    }
}