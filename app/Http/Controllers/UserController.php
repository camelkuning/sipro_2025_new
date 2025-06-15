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
        $jumlahKebijakan = ProgramKerja::where('status', 'aktif')->sum('tambahan_dana_kebijakan');

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

        $colorMap = [
            'Persekutuan Anak Muda' => '#10b981', // Hijau
            'Persekutuan Anak dan Remaja' => '#f59e0b',        // Kuning orange
            'Persekutuan Kaum Bapak' => '#3b82f6',           // Biru
            'Persekutuan Wanita' => '#8b5cf6',            // Ungu
            'Majelis Jemaat' => '#e74c3c',               // Merah
        ];
        $jadwalProker = ProgramKerja::all();

        $events = $jadwalProker->map(function ($item) use ($colorMap) {
            $warna = $colorMap[$item->komisi_program_kerja] ?? '#6b7280'; // abu-abu default

            return [
                'title' => $item->komisi_program_kerja,
                'start' => \Carbon\Carbon::parse($item->tanggal_mulai)->toIso8601String(),
                'end' => $item->tanggal_mulai != $item->tanggal_selesai
                    ? \Carbon\Carbon::parse($item->tanggal_selesai)->addDay()->toIso8601String()
                    : null,
                'color' => $warna,
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


        return view('user.dashboard-proker', compact('tahunIni', 'jumlahAktif', 'jumlahSelesai', 'budget_berjalan', 'alokasi_tahun_depan', 'tahunBudget', 'jumlahDigunakan', 'jadwalProker', 'budgetData', 'alokasiData', 'events', 'tahunList', 'tahunDipilih', 'daftarAkun', 'programKerja',
            'jumlahKebijakan' ));
    }

    public function dashboardKeuanganUser(Request $request)
    {
        $akunDipilih = $request->akun_id;
        $query = DB::table($namaTabel ?? 'keuangan');

        if ($akunDipilih) {
            $query->where('akun_id', $akunDipilih);
        }

        // $keuangan = $query->orderBy('tanggal', 'asc')
        //     ->orderBy('urutan', 'asc')
        //     ->get();

        $acuan = DB::table('acuan_pembagian')
            ->get()
            ->keyBy('kategori');
        // dd($acuan);
        $sinode = $acuan['Sinode']->persentase ?? 0;
        $klasis = $acuan['Klasis']->persentase ?? 0;
        $program = $acuan['Program Kerja']->persentase ?? 0;
        $belanja = $acuan['Belanja Rutin Gereja']->persentase ?? 0;
        //  dd($sinode, $klasis, $program, $belanja);    


        // Ambil semua tabel yang memiliki prefix 'keuangan_'
        // 🔍 Ambil semua tabel keuangan_YYYY
        $tables = DB::select("SHOW TABLES LIKE 'keuangan_%'");
        $tahunList = collect($tables)->map(function ($table) {
            return str_replace('keuangan_', '', collect($table)->first());
        })->sortDesc()->values();

        // 🏷 Tahun dipilih (jika ada)
        $tahunDipilih = $request->tahun;
        $keuanganModel = new Keuangan();

        if ($tahunDipilih && Schema::hasTable("keuangan_$tahunDipilih")) {
            $keuanganModel->setTable("keuangan_$tahunDipilih");
        } else {
            $tahunDipilih = "Keuangan Sekarang";
            $keuanganModel->setTable("keuangan");
        }

        // 📊 Query data keuangan dengan relasi akun

        // $query = $keuanganModel->with('akun')->orderBy('tanggal', 'asc');
        $query = $keuanganModel->orderBy('tanggal', 'asc');

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $dari = Carbon::createFromFormat('d/m/Y', $request->dari_tanggal)->format('Y-m-d');
            $sampai = Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal)->format('Y-m-d');
            $query->whereBetween('tanggal', [$dari, $sampai]);
        }

        $dataKeuangan = $query->get();

        // 💰 Statistik Keuangan
        $totalKredit = (clone $keuanganModel)->where('tipe', 'kredit')
            ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])->sum('jumlah');

        $totalDebit = (clone $keuanganModel)->where('tipe', 'debit')
            ->where('kode_keuangan', 'not like', 'PROKER%')->sum('jumlah');

        $totalSaldo = (clone $keuanganModel)->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
            ->orderByDesc('tanggal')->orderByDesc('id')->value('saldo_akhir') ?? 0;

        $debitProker = (clone $keuanganModel)->where('tipe', 'debit')
            ->where('kode_keuangan', 'like', 'PROKER%')->sum('jumlah');

        // 🔄 Cek bulan terakhir dan status pembagian
        $lastKredit = DB::table('keuangan')->where('tipe', 'kredit')->orderBy('tanggal', 'desc')->first();

        $bulanTerakhir = null;
        $tahunTerakhir = null;
        $sudahDibagi = true;

        $latestKredit = DB::table('keuangan')
            ->orderBy('tanggal', 'desc')
            ->first();


        $currentMonth = null;
        $currentYear = null;

        if ($latestKredit) {
            $currentMonth = Carbon::parse($latestKredit->tanggal)->month;
            $currentYear = Carbon::parse($latestKredit->tanggal)->year;
        }

        $bulanBelumDiproses = DB::table('keuangan')
            ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
            ->where('tipe', 'kredit')->groupBy('tahun', 'bulan')->get()
            ->filter(function ($item) use ($currentMonth, $currentYear) {
                if (is_null($currentMonth) || is_null($currentYear)) {
                    return true;
                }
                return $item->tahun < $currentYear ||
                    ($item->tahun == $currentYear && $item->bulan < $currentMonth);
            });

        $daftarAkun = Akun::all();


        return view('user.dashboard-keuangan', compact(
            'dataKeuangan',
            'totalSaldo',
            'totalKredit',
            'totalDebit',
            'tahunList',
            'tahunDipilih',
            'bulanTerakhir',
            'tahunTerakhir',
            'acuan',
            'debitProker',
            'daftarAkun',
            'akunDipilih',
            'sinode',
            'klasis',
            'program',
            'belanja',
        ));
    }

    public function getChartPembagian()
    {
        $data = KeuanganHelper::getChartPembagian();
        return response()->json($data);
    }
}
