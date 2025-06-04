<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Keuangan;
use App\Models\LogPembagianBulanan;
use App\Models\AcuanPembagian;
use App\Models\ProgramKerjaBudget;

use Carbon\Carbon;

class KeuanganHelper
{
    public static function getChartData()
    {
        return DB::table('keuangan')
            ->selectRaw('
        MONTH(tanggal) as bulan,
        SUM(CASE WHEN tipe = "kredit" THEN jumlah ELSE 0 END) as total_kredit,
        SUM(CASE WHEN tipe = "debit" THEN jumlah ELSE 0 END) as total_debit
    ')
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('MONTH(tanggal)'))
            ->get();
    }

    public static function getChartPembagian()
    {
        $acuan = DB::table('acuan_pembagian')->get()->keyBy('kategori');

        return [
            'Sinode' => $acuan['Sinode']->persentase ?? 0,
            'Klasis' => $acuan['Klasis']->persentase ?? 0,
            'Program Kerja' => $acuan['Program Kerja']->persentase ?? 0,
            'Belanja Rutin Gereja' => $acuan['Belanja Rutin Gereja']->persentase ?? 0,
        ];
    }

    public static function getSaldoAkhirTerakhir($tanggal = null)
    {
        $query = Keuangan::query();

        if ($tanggal) {
            $query->where('tanggal', '<=', $tanggal);
        }

        $lastKeuangan = $query->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return $lastKeuangan ? $lastKeuangan->saldo_akhir : 0;
    }

    //getChartProgramkerja
    public static function getChartProgramKerja()
    {
        return DB::table('program_kerja')
        ->select('nama_program_kerja', 'anggaran_digunakan')
        ->whereNotNull('tanggal_mulai')
        ->orderBy('anggaran_digunakan', 'desc') // opsional: urutkan dari yang terbesar
        ->get();
    }
}
