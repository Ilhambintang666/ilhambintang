<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {

        User::updateOrCreate(
            ['email' => 'admin@pmi.org'], // cek jika sudah ada
            [
                'name' => 'Admin PMI Semarang',
                'password' => Hash::make('password'),
                'role' => 'admin'
                
            ]
            
        );
    }
}
