<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@tanamanobat.id',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Editor Tanaman',
            'email'     => 'editor@tanamanobat.id',
            'password'  => Hash::make('password'),
            'role'      => 'editor',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Pengguna Demo',
            'email'     => 'user@tanamanobat.id',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'is_active' => true,
        ]);
    }
}
