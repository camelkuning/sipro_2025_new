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
    // /**
    //  * Hitung saldo awal akun sampai sebelum tanggal tertentu
    //  *
    //  * @param int $akunId
    //  * @param string $tanggal format Y-m-d
    //  * @return float
    //  */
    // public static function saldoAwal(int $akunId, string $tanggal): float
    // {
    //     $saldo = DB::table('keuangan')
    //         ->where('akun_id', $akunId)
    //         ->where('tanggal', '<', $tanggal)
    //         ->selectRaw("SUM(CASE WHEN tipe = 'kredit' THEN jumlah ELSE -jumlah END) as saldo")
    //         ->value('saldo');

    //     return $saldo ?? 0;
    // }

    // /**
    //  * Hitung saldo akhir dari saldo awal + transaksi saat ini
    //  *
    //  * @param float $saldoAwal
    //  * @param string $tipe 'kredit' atau 'debit'
    //  * @param float $jumlah
    //  * @return float
    //  */
    // public static function saldoAkhir(float $saldoAwal, string $tipe, float $jumlah): float
    // {
    //     if ($tipe === 'kredit') {
    //         return $saldoAwal + $jumlah;
    //     }
    //     return $saldoAwal - $jumlah;
    // }

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
}
