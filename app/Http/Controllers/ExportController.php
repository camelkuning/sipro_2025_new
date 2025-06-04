<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Keuangan;

class ExportController extends Controller
{
    public function exportKeuanganPDF(Request $request)
    {
        $tahunDipilih = $request->tahun;
        $namaTabel = $tahunDipilih ? "keuangan_$tahunDipilih" : "keuangan";

        if (!Schema::hasTable($namaTabel)) {
            return redirect()->back()->with('error', 'Data keuangan tidak tersedia.');
        }

        $dataKeuangan = DB::table($namaTabel)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalKredit = DB::table($namaTabel)
            ->where('tipe', 'kredit')
            ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
            ->sum('jumlah');

        $totalDebit = DB::table($namaTabel)
            ->where('tipe', 'debit')
            ->where('kode_keuangan', 'not like', 'PROKER%')
            ->sum('jumlah');

        $totalSaldo = DB::table($namaTabel)
            ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->value('saldo_akhir') ?? 0;

        $debitProker = DB::table($namaTabel)
            ->where('tipe', 'debit')
            ->where('kode_keuangan', 'like', 'PROKER%')
            ->sum('jumlah');

        $pdf = Pdf::loadView('export.export-pdf-keuangan', compact(
            'dataKeuangan',
            'totalKredit',
            'totalDebit',
            'totalSaldo',
            'debitProker',
            'tahunDipilih'
        ))->setPaper('A4', 'landscape');

        return $pdf->download("Laporan_Keuangan_$tahunDipilih.pdf");
    }
}
