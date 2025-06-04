<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Helpers\KeuanganHelper;
use Carbon\Carbon;
use App\Models\ProgramKerjaBudget;
use App\Models\ProgramKerja;
use App\Models\Akun;
use App\Models\AcuanPembagian;


class UserController extends Controller
{

    public function getChartData()
    {
        $data = KeuanganHelper::getChartData();
        return response()->json($data);
    }

    public function dashboardProkerUser(Request $request)
    {
        $daftarAkun = Akun::all();
        $tahunIni = now()->year;

        $tables = DB::select("SHOW TABLES LIKE 'program_kerja_%'");

        $tahunList = collect($tables)->map(function ($table) {
            $tableName = collect($table)->first();
            return str_replace('program_kerja_', '', $tableName);
        })->filter(function ($tahunfilter) {
            // Ambil hanya yang formatnya tahun (misal "2024", "2025")
            return preg_match('/^\d{4}$/', $tahunfilter);
        })->sortDesc()->values();

        $tahunDipilih = $request->tahun;

        $jumlahAktif = ProgramKerja::where('status', 'aktif')->count();
        $jumlahSelesai = ProgramKerja::where('status', 'selesai')->count(); //buat nanti ulang tabelnya biar bisa ada ini 
        $jumlahDigunakan = ProgramKerja::where('status', 'aktif')->sum('anggaran_digunakan');

        $budgetData = ProgramKerjaBudget::whereNotNull('tahun_budget')
            ->orderBy('tahun_budget', 'desc')
            ->first();
        $tahunBudget = $budgetData ? $budgetData->tahun_budget : null;

        $alokasiData = ProgramKerjaBudget::where('tahun_alokasi', $tahunIni + 1)->first();

        $budget_berjalan = $budgetData ? $budgetData->budget_berjalan : 0;
        $alokasi_tahun_depan = $alokasiData ? $alokasiData->alokasi_tahun_depan : 0;

        $jadwalProker = ProgramKerja::select('nama_program_kerja', 'tanggal_mulai', 'tanggal_selesai')
            ->whereNotNull('tanggal_mulai')
            ->get();

        $colors = ['#1abc9c', '#3498db', '#f39c12', '#9b59b6', '#e74c3c'];
        $jadwalProker = ProgramKerja::all();
        $events = $jadwalProker->map(function ($item, $index) use ($colors) {
            return [
                'title' => $item->komisi_program_kerja,
                'start' => \Carbon\Carbon::parse($item->tanggal_mulai)->toIso8601String(),
                'end' => $item->tanggal_mulai != $item->tanggal_selesai
                    ? \Carbon\Carbon::parse($item->tanggal_selesai)->addDay()->toIso8601String()
                    : null,
                'color' => $colors[$index % count($colors)],
                'textColor' => '#fff',

                'nama_program_kerja' => $item->nama_program_kerja,
            ];
        });

         if ($tahunDipilih) {
            // Kalau ada tahun dipilih, kita cek ke tabel arsip program_kerja_YYYY
            $namaTabel = 'program_kerja_' . $tahunDipilih;

            if (Schema::hasTable($namaTabel)) {
                // Ambil data yang sudah diarsipkan saja
                $programKerja = DB::table($namaTabel)->where('status', 'diarsipkan')->get();
            } else {
                $programKerja = collect(); // Kalau tabel nggak ada, kosongin aja
            }
        } else {
            // Kalau tidak pilih tahun (artinya "Proker Sekarang"), ambil dari tabel utama
            $tahunSekarang = now()->year;

            if (Schema::hasTable('program_kerja')) {
                $programKerja = ProgramKerja::with('akun')->get();
                // ->where('status', 'aktif')
                // ->where('tahun_budget', $tahunSekarang) // ⛳ penting agar tidak ambil tahun 2025 dll

            } else {
                $programKerja = collect();
            }

            $tahunDipilih = "Proker Sekarang";
        }


        return view('user.dashboard-proker', compact('tahunIni', 'jumlahAktif', 'jumlahSelesai', 'budget_berjalan', 'alokasi_tahun_depan', 'tahunBudget', 'jumlahDigunakan', 'jadwalProker', 'budgetData', 'alokasiData', 'events', 'tahunList', 'tahunDipilih', 'daftarAkun', 'programKerja'));
    }

    public function dashboardKeuanganUser(Request $request)
    {
        // Ambil daftar tahun dari tabel keuangan arsip
        $tables = DB::select("SHOW TABLES LIKE 'keuangan_%'");
        $tahunList = collect($tables)->map(function ($table) {
            $tableName = collect($table)->first();
            return str_replace('keuangan_', '', $tableName);
        })->sortDesc()->values();

        $tahunDipilih = $request->tahun;

        if ($tahunDipilih) {
            $namaTabel = "keuangan_$tahunDipilih";
            if (Schema::hasTable($namaTabel)) {
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
            } else {
                $dataKeuangan = collect();
                $totalKredit = 0;
                $totalDebit = 0;
                $totalSaldo = 0;
                $debitProker = 0;
            }
        } else {
            $dataKeuangan = DB::table('keuangan')
                ->orderBy('tanggal', 'asc')
                ->get();

            $totalKredit = DB::table('keuangan')
                ->where('tipe', 'kredit')
                ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
                ->sum('jumlah');

            $totalDebit = DB::table('keuangan')
                ->where('tipe', 'debit')
                ->where('kode_keuangan', 'not like', 'PROKER%')
                ->sum('jumlah');

            $totalSaldo = DB::table('keuangan')
                ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->value('saldo_akhir') ?? 0;

            $debitProker = DB::table('keuangan')
                ->where('tipe', 'debit')
                ->where('kode_keuangan', 'like', 'PROKER%')
                ->sum('jumlah');

            $tahunDipilih = "Keuangan Sekarang";
        }

        $keuanganModel = new Keuangan();
        if ($tahunDipilih && Schema::hasTable("keuangan_$tahunDipilih")) {
            $keuanganModel->setTable("keuangan_$tahunDipilih");
        }

        $query = $keuanganModel->with('akun');

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $dari = \Carbon\Carbon::createFromFormat('d/m/Y', $request->dari_tanggal)->format('Y-m-d');
            $sampai = \Carbon\Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal)->format('Y-m-d');

            $query->whereBetween('tanggal', [$dari, $sampai]);
        }

        $dataTanggal = $query->get();


        return view('user.dashboard-keuangan', compact(
            'dataTanggal',
            'dataKeuangan',
            'totalKredit',
            'totalDebit',
            'totalSaldo',
            'debitProker',
            'tahunList',
            'tahunDipilih'
        ));
    }

    public function getChartPembagian()
    {
        $data = KeuanganHelper::getChartPembagian();
        return response()->json($data);
    }
}
