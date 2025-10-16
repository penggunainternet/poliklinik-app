<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
        $user = [
        [
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ],
        [
            'nama' => 'Dokter',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('dokter'),
            'role' => 'dokter'
        ],
        [
            'nama' => 'Pasien',
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('pasien'),
            'role' => 'pasien'
        ]
        
    ];
    foreach($user as $user){
        User::create($user);   
    }
    }
}