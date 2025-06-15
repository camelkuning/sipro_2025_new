<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Keuangan;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportKeuanganPDF(Request $request)
    {
        // dd($request->tahun);
        $tahunDipilih = $request->tahun;
// dd($tahunDipilih);
        // Logika menentukan nama tabel berdasarkan isi tahunDipilih
        if ($tahunDipilih === 'Keuangan Sekarang') {
            $namaTabel = 'keuangan';
        } else {
            $namaTabel = "keuangan_$tahunDipilih";
        }
        if (!Schema::hasTable($namaTabel)) {
            return redirect()->back()->with('error', 'Data keuangan tidak tersedia.');
        }
// dd($tahunDipilih);
        // =========================================
        // Cek jika filter tanggal dipakai
        // =========================================
        $query = DB::table($namaTabel)->orderBy('tanggal', 'asc');

        $dari = null;
        $sampai = null;

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $dari = Carbon::createFromFormat('d/m/Y', $request->dari_tanggal)->format('Y-m-d');
            $sampai = Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal)->format('Y-m-d');
            $query->whereBetween('tanggal', [$dari, $sampai]);
        }

        $dataKeuangan = $query->get();
        //  dd($dataKeuangan);

        // Total Kredit
        $totalKredit = DB::table($namaTabel)
            ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                return $q->whereBetween('tanggal', [$dari, $sampai]);
            })
            ->where('tipe', 'kredit')
            ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
            ->sum('jumlah');

        // Total Debit
        $totalDebit = DB::table($namaTabel)
            ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                return $q->whereBetween('tanggal', [$dari, $sampai]);
            })
            ->where('tipe', 'debit')
            ->where('kode_keuangan', 'not like', 'PROKER%')
            ->sum('jumlah');

        // Saldo Akhir terakhir (ambil satu terakhir dalam rentang, jika ada filter)
        $totalSaldo = DB::table($namaTabel)
            ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
            ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                return $q->whereBetween('tanggal', [$dari, $sampai]);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->value('saldo_akhir') ?? 0;

        // Pengeluaran untuk PROKER
        $debitProker = DB::table($namaTabel)
            ->when($dari && $sampai, function ($q) use ($dari, $sampai) {
                return $q->whereBetween('tanggal', [$dari, $sampai]);
            })
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



        $rangeLabel = '';
        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $rangeLabel = '_' . str_replace('/', '-', $request->dari_tanggal) . '_sd_' . str_replace('/', '-', $request->sampai_tanggal);
        }

        return $pdf->download("Laporan_Keuangan_{$tahunDipilih}{$rangeLabel}.pdf");
    }
}
