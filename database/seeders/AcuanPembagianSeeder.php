<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcuanPembagian;
use App\Models\Akun;

class AcuanPembagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run()
    {
        // Map kategori ke kode akun agar bisa cari akun secara otomatis
        $akunMap = [
            'Sinode' => 'SINODE2025',
            'Klasis' => 'KLASIS2025',
            'Program Kerja' => 'PROKER2025',
            'Belanja Rutin Gereja' => 'BELANJA2025',
        ];

        $data = [
            ['kategori' => 'Sinode', 'persentase' => 40],
            ['kategori' => 'Klasis', 'persentase' => 20],
            ['kategori' => 'Program Kerja', 'persentase' => 30],
            ['kategori' => 'Belanja Rutin Gereja', 'persentase' => 10],
        ];

        foreach ($data as $item) {
            $akunKode = $akunMap[$item['kategori']] ?? null;
            $akun = $akunKode ? Akun::where('kode_akun', $akunKode)->first() : null;

            AcuanPembagian::updateOrCreate(
                ['kategori' => $item['kategori']],
                [
                    'persentase' => $item['persentase'],
                    'akun_id' => $akun ? $akun->id : null,
                ]
            );
        }
    }
}
