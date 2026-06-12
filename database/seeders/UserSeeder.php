<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nipy' => '12345',
            'nama_lengkap' => 'Administrator',
            'email' => null,
            'jabatan' => 'Administrator Sistem',
            'role' => 'admin',
            'status' => 'aktif',
            'password' => 'password123',
        ]);
    }
}
