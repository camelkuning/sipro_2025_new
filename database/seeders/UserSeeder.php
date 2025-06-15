<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama_lengkap' => 'Admin Gereja',
                'komisi' => 'bendahara',
                'role' => 'admin',
                'username' => 'viadolorosa',
                'password' => Hash::make('11111111'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Gamaliel Lexi',
                'komisi' => 'Persekutuan Anak Muda',
                'role' => 'user',
                'username' => 'pamviadolorosa',
                'password' => Hash::make('22222222'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
