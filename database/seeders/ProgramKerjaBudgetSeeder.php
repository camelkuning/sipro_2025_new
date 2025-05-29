<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramKerjaBudget;

class ProgramKerjaBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data untuk tahun 2025
        ProgramKerjaBudget::create([
            'akun_id' => 1, // ID akun yang sesuai
            'tahun_alokasi' => 2026,
            // Contoh dana alokasi tahun depan (misalnya 30% dari total pemasukan)
            'tahun_budget' => 2025,
            'budget_berjalan' => 10000000, // Dana program kerja untuk tahun berjalan
        ]);

        // // Menambahkan data untuk tahun 2026
        // ProgramKerjaBudget::create([
        //     'tahun_alokasi' => 2026,
        //     'alokasi_tahun_depan' => 35000000, // Contoh dana alokasi tahun depan untuk tahun 2026
        //     'tahun_budget' => 2026,
        //     'budget_berjalan' => 12000000, // Dana program kerja untuk tahun 2026
        // ]);

    }
}
