<?php

namespace App\Http\Controllers;


use App\Models\Keuangan;
use Illuminate\Http\Request;
use App\Models\Users;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Exports\KeuanganExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Helpers\KeuanganHelper;
use Carbon\Carbon;
use App\Models\LogPembagianBulanan;
use App\Models\ProgramKerjaBudget;
use App\Models\ProgramKerja;
use App\Models\AcuanPembagian;
use Illuminate\Support\Facades\Log;
use App\Models\Akun;
use App\Models\KeuanganDinamis;



class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    public function getChartData()
    {
        $data = KeuanganHelper::getChartData();
        return response()->json($data);
    }

    public function chartDataProker()
    {
        $data = KeuanganHelper::getChartProgramKerja();
        return response()->json($data);
    }



    public function dashboardProkerA(Request $request)
    {
        $tahunIni = now()->year;

        $jumlahAktif = ProgramKerja::where('status', 'aktif')->count();
        $jumlahSelesai = ProgramKerja::where('status', 'diarsipkan')->count(); //buat nanti ulang tabelnya biar bisa ada ini 
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


        return view('admin.dashboard-proker', compact('tahunIni', 'jumlahAktif', 'jumlahSelesai', 'budget_berjalan', 'alokasi_tahun_depan', 'tahunBudget', 'jumlahDigunakan', 'jadwalProker', 'budgetData', 'alokasiData', 'events', 'jumlahKebijakan'));
    }

    public function dashboardKeuanganA(Request $request)
    {
        // Ambil daftar tahun dari tabel keuangan arsip
        $acuan = DB::table('acuan_pembagian')
            ->get()
            ->keyBy('kategori');
        // dd($acuan);
        $sinode = $acuan['Sinode']->persentase ?? 0;
        $klasis = $acuan['Klasis']->persentase ?? 0;
        $program = $acuan['Program Kerja']->persentase ?? 0;
        $belanja = $acuan['Belanja Rutin Gereja']->persentase ?? 0;
        //  dd($sinode, $klasis, $program, $belanja);    


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


        return view('admin.dashboard-keuangan', compact(
            'dataTanggal',
            'dataKeuangan',
            'totalKredit',
            'totalDebit',
            'totalSaldo',
            'debitProker',
            'tahunList',
            'tahunDipilih',
            'sinode',
            'klasis',
            'program',
            'belanja'
        ));
    }



    public function daftarUser()
    {
        $users = Users::where('role', 'user')->get();
        return view('admin.daftaruser', compact('users'));
    }

    public function addDaftarUser()
    {
        return view('admin.add-daftaruser');
    }

    public function postDaftarUser(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'komisi' => ['required', Rule::in([
                'Persekutuan Anak Muda',
                'Persekutuan Anak dan Remaja',
                'Persekutuan Kaum Bapa',
                'Persekutuan Wanita'
            ])],
            'role' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
        ]);

        Users::create([
            'nama_lengkap' => $request->nama_lengkap,
            'komisi' => $request->komisi,
            'role' => $request->role,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return redirect('/daftarUser')->with('success', 'User Berhasil Ditambahkan!');
    }

    public function editUser($id)
    {
        $user = Users::findOrFail($id);
        return view('admin.edit-daftaruser', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'komisi' => 'required',
            'role' => 'required',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|min:6|confirmed',
        ]);


        $user = Users::findOrFail($id);


        $user->nama_lengkap = $request->nama_lengkap;
        $user->komisi = $request->komisi;
        $user->role = $request->role;
        $user->username = $request->username;


        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/daftarUser')->with('success', 'User Berhasil Diperbarui!');
    }

    public function cariUser($id)
    {
        $user = Users::findOrFail($id);
        return response()->json($user);
    }

    public function deleteUser($id)
    {
        $user = Users::findOrFail($id);
        $user->delete();

        return redirect('/daftarUser')->with('success', 'User Berhasil Dihapus!');
    }


    public function daftarProkerA(Request $request)
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


        // Ambil data budget dari tabel program_kerja_budget berdasarkan tahun saat ini (budget berjalan)
        $budgetData = ProgramKerjaBudget::whereNotNull('tahun_budget')
            ->orderBy('tahun_budget', 'desc')
            ->first();
        $tahunBudget = $budgetData ? $budgetData->tahun_budget : null;
        // Ambil data alokasi_tahun_depan dari tahun depan (tahun ini + 1)
        $alokasiData = ProgramKerjaBudget::where('tahun_alokasi', $tahunIni + 1)->first();

        // Nilai default jika datanya belum ada
        $budget_berjalan = $budgetData ? $budgetData->budget_berjalan : 0;

        $alokasi_tahun_depan = $alokasiData ? $alokasiData->alokasi_tahun_depan : 0;

        // dd([
        //     'tahunDipilih' => $tahunDipilih,
        //     'data' => $programKerja,
        //     'namaTabel' => $tahunDipilih ? "program_kerja_$tahunDipilih" : 'program_kerja',
        // ]);

        return view('admin.daftarproker', compact('programKerja', 'alokasi_tahun_depan', 'budget_berjalan', 'tahunIni', 'tahunList', 'tahunDipilih', 'tahunBudget', 'daftarAkun'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'akun_id' => 'required|exists:akun,id',
            'kode_program_kerja' => 'unique:program_kerja',
            'nama_program_kerja' => 'required',
            'komisi_program_kerja' => 'required',
            'nama_ketua_program_kerja' => 'required',
            'nama_majelis_pendamping' => 'required',
            'tanggal_mulai' => 'nullable',
            'tanggal_selesai' => 'nullable',
            'keterangan' => 'nullable',
            'anggaran_digunakan' => 'nullable|numeric',
            'tambahan_dana_kebijakan' => 'nullable|numeric',
            'tahun' => 'required|integer',
            'status' => 'required|in:aktif,selesai',

        ]);


        // 1. Ambil tahun dari request
        $tahun = $request->tahun;

        // 2. Cari jumlah program kerja yang sudah ada di tahun itu
        $jumlahSebelumnya = ProgramKerja::where('tahun', $tahun)->count() + 1;

        // 3. Buat kode program kerja otomatis, contoh: PROKER2025-001
        $kodeBaru = 'PROKER' . $tahun . '-' . str_pad($jumlahSebelumnya, 3, '0', STR_PAD_LEFT);

        ProgramKerja::create([
            'akun_id' => $request->akun_id,
            'kode_program_kerja' => $kodeBaru,
            'nama_program_kerja' => $request->nama_program_kerja,
            'komisi_program_kerja' => $request->komisi_program_kerja,
            'nama_ketua_program_kerja' => $request->nama_ketua_program_kerja,
            'nama_majelis_pendamping' => $request->nama_majelis_pendamping,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'anggaran_digunakan' => $request->anggaran_digunakan,
            'tambahan_dana_kebijakan' => $request->tambahan_dana_kebijakan,
            'status' => $request->status,
            'tahun' => $request->tahun,
        ]);


        $tahunIni = date('Y');

        // $budget = ProgramKerjaBudget::where('tahun_budget', $tahunIni)->first();
        $budget = ProgramKerjaBudget::first();

        $anggaranDigunakan = (int) ($request->anggaran_digunakan ?? 0);
        $tambahanDanaKebijakan = (int) ($request->tambahan_dana_kebijakan ?? 0);

        $totalJumlah = $anggaranDigunakan + $tambahanDanaKebijakan;

        if ($budget && $totalJumlah > 0) {
            $budget->budget_berjalan -= $totalJumlah;
            $budget->save();
        }

        $transaksiSebelumnya = DB::table('keuangan')
            ->where('kode_keuangan', 'not like', 'PROKER%')
            ->orderByDesc('id')
            ->first();

        $saldoAwal = $transaksiSebelumnya ? $transaksiSebelumnya->saldo_akhir : 0;
        $saldoAkhir = $saldoAwal;

        $tanggalTransaksi = now()->toDateString();

        if (!Keuangan::where('kode_keuangan', $kodeBaru)->exists() && $totalJumlah > 0) {
            Keuangan::create([
                'akun_id' => $request->akun_id,
                'kode_keuangan' => $kodeBaru,
                'tanggal' => $tanggalTransaksi,
                'tipe' => 'debit',
                'jumlah' => $totalJumlah,
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => 'Anggaran Program Kerja: ' . $request->nama_program_kerja,
            ]);
        }

        return redirect()->back()->with('success', 'Program Kerja berhasil ditambahkan.');
    }

    public function editProker($id)
    {
        $programKerja = ProgramKerja::where('kode_program_kerja', $id)->firstOrFail();
        return view('admin.edit-daftarproker', compact('programKerja'));
    }

    public function deleteProker($id)
    {

        $programKerja = ProgramKerja::where('id', $id)->firstOrFail();
        $namaProker = $programKerja->nama_program_kerja;

        $anggaranDigunakan = $programKerja->anggaran_digunakan;
        $tambahanDanaKebijakan = $programKerja->tambahan_dana_kebijakan;

        $budget = ProgramKerjaBudget::where('tahun_budget', $programKerja->tahun)->first();

        if ($budget) {
            // Kembalikan anggaran yang telah digunakan
            $budget->budget_berjalan += $anggaranDigunakan;
            // Kembalikan tambahan dana kebijakan yang telah digunakan
            $budget->budget_berjalan += $tambahanDanaKebijakan;
            $budget->save();
        }
        // Hapus program krja
        $programKerja->delete();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', "Program Kerja '$namaProker' berhasil dihapus dan anggaran dikembalikan.");
    }

    // Update Program Kerja
    public function updateProker(Request $request, $id)
    {

        // dd($id);
        $request->validate([

            'nama_program_kerja' => 'required|string|max:255',
            'komisi_program_kerja' => 'required|string|max:255',
            'nama_ketua_program_kerja' => 'required|string|max:255',
            'nama_majelis_pendamping' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'anggaran_digunakan' => 'required|numeric',
            'tahun' => 'required|digits:4',
            'status' => 'required|in:aktif,selesai',

        ]);


        $item = ProgramKerja::where('id', $id)->firstOrFail();
        // dd($item);

        $item->update([
            'nama_program_kerja' => $request->nama_program_kerja,
            'komisi_program_kerja' => $request->komisi_program_kerja,
            'nama_ketua_program_kerja' => $request->nama_ketua_program_kerja,
            'nama_majelis_pendamping' => $request->nama_majelis_pendamping,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'anggaran_digunakan' =>  $request->anggaran_digunakan,
            'tambahan_dana_kebijakan' =>  $request->tambahan_dana_kebijakan,
            'tahun' => $request->tahun,
            'status' => $request->status,
        ]);

        return redirect('/daftarProker')->with('success', 'Program Kerja berhasil diperbarui!');
    }


    // Tambah pengeluaran (debit) untuk program kerja
    public function updatePengeluaran(Request $request, $kode_program_kerja)
    {
        $request->validate([
            'jumlah_pengeluaran' => 'required|numeric|min:0'
        ]);

        $program = ProgramKerja::where('kode_program_kerja', $kode_program_kerja)->firstOrFail();
        $jumlah = $request->input('jumlah_pengeluaran');

        // Kurangi dari budget berjalan jika mencukupi
        $budget = ProgramKerjaBudget::where('tahun', $program->tahun)->first();
        if ($budget && $budget->budget_berjalan >= $jumlah) {
            $budget->budget_berjalan -= $jumlah;
            $budget->save();
            $program->anggaran_digunakan += $jumlah;
            $program->save();
            return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat.');
        }

        return redirect()->back()->with('error', 'Budget berjalan tidak mencukupi.');
    }

    // Tambah dana kebijakan jika anggaran tidak mencukupi
    public function tambahDanaKebijakan(Request $request, $kode_program_kerja)
    {
        //tidak pakai js karena jsnya tidak berfungsi entah kenapa
        $request->merge([
            'tambahan_dana_kebijakan' => str_replace('.', '', $request->tambahan_dana_kebijakan)
        ]);

        $request->validate([
            'tambahan_dana_kebijakan' => 'required|numeric',
            'keterangan' => 'nullable|string'
        ]);

        $program = ProgramKerja::where('kode_program_kerja', $kode_program_kerja)->firstOrFail();

        // Tambah kebijakan dan perbarui keterangan
        $program->tambahan_dana_kebijakan += $request->tambahan_dana_kebijakan;

        // Tambahkan catatan ke keterangan, jika ada
        if ($request->filled('keterangan')) {
            $program->keterangan = trim($program->keterangan . "\n" . "[Kebijakan]" . $request->keterangan);
        }

        $program->save();

        $tahunIni = date('Y');
        $budget = ProgramKerjaBudget::where('tahun_budget', $tahunIni)->first();



        if ($budget && $request->tambahan_dana_kebijakan) {
            $budget->budget_berjalan -= (int) $request->tambahan_dana_kebijakan;
            $budget->save();
        }
        // dd($budget);

        return redirect()->back()->with('success', 'Dana kebijakan berhasil ditambahkan.');
    }



    public function daftarKeuanganA(Request $request)
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


        // Query data keuangan dengan relasi akun
        // $query = $keuanganModel->with('akun')->orderBy('tanggal', 'asc');
        $query = $keuanganModel->orderBy('tanggal', 'asc');

        if ($request->filled('dari_tanggal') && $request->filled('sampai_tanggal')) {
            $dari = Carbon::createFromFormat('d/m/Y', $request->dari_tanggal)->format('Y-m-d');
            $sampai = Carbon::createFromFormat('d/m/Y', $request->sampai_tanggal)->format('Y-m-d');
            $query->whereBetween('tanggal', [$dari, $sampai]);
        }

        $dataKeuangan = $query->get();

        //  Statistik Keuangan
        $totalKredit = (clone $keuanganModel)->where('tipe', 'kredit')
            ->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])->sum('jumlah');

        $totalDebit = (clone $keuanganModel)->where('tipe', 'debit')
            ->where('kode_keuangan', 'not like', 'PROKER%')->sum('jumlah');

        $totalSaldo = (clone $keuanganModel)->whereNotIn('keterangan', ['Alokasi Dana Belanja Gereja'])
            ->orderByDesc('tanggal')->orderByDesc('id')->value('saldo_akhir') ?? 0;

        $debitProker = (clone $keuanganModel)->where('tipe', 'debit')
            ->where('kode_keuangan', 'like', 'PROKER%')->sum('jumlah');

        //  Cek bulan terakhir dan status pembagian
        $lastKredit = DB::table('keuangan')->where('tipe', 'kredit')->orderBy('tanggal', 'desc')->first();

        $bulanTerakhir = null;
        $tahunTerakhir = null;
        $sudahDibagi = true;

        if ($lastKredit) {
            $tanggal = Carbon::parse($lastKredit->tanggal);
            $bulanTerakhir = $tanggal->month;
            $tahunTerakhir = $tanggal->year;

            $sudahDibagi = LogPembagianBulanan::where('bulan', $bulanTerakhir)
                ->where('tahun', $tahunTerakhir)->exists();
        }

        $bulanLalu = Carbon::now()->subMonth();
        $sudahDiproses = LogPembagianBulanan::where('tahun', $bulanLalu->year)
            ->where('bulan', $bulanLalu->month)->exists();

        $tahunIni = now()->year;

        $bulanUnik = DB::table('keuangan')
            ->where('tipe', 'kredit')->whereYear('tanggal', $tahunIni)
            ->selectRaw('MONTH(tanggal) as bulan')->distinct()->pluck('bulan');

        $sudah12Bulan = $bulanUnik->count() === 12;

        // 📆 Data bulan yang belum diproses
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
            ->filter(function ($item) {
                return !DB::table('log_pembagian_bulanan')
                    ->where('tahun', $item->tahun)->where('bulan', $item->bulan)->exists();
            })
            ->filter(function ($item) use ($currentMonth, $currentYear) {
                if (is_null($currentMonth) || is_null($currentYear)) {
                    return true;
                }
                return $item->tahun < $currentYear ||
                    ($item->tahun == $currentYear && $item->bulan < $currentMonth);
            });

        $daftarAkun = Akun::all();

        // dd($dataKeuangan);
        //     dd(['dataTanggal' => $dataTanggal,
        //         'dataKeuangan' => $dataKeuangan,

        // ]);


        // dd([
        //     'tahunDipilih' => $tahunDipilih,
        //     'data' => $dataKeuangan->count(),
        //     'namaTabel' => $tahunDipilih ? "keuangan_$tahunDipilih" : 'keuangan',
        // ]);
        // $totalKredit = $akun->keuangans()->where('tipe', 'kredit')->sum('jumlah');
        // $totalDebit = $akun->keuangans()->where('tipe', 'debit')->sum('jumlah');
        // $saldo = $totalKredit - $totalDebit;


        return view('admin.daftarkeuangan', compact(
            // 'dataTanggal',
            'dataKeuangan',
            'totalSaldo',
            'totalKredit',
            'totalDebit',
            'tahunList',
            'tahunDipilih',
            'bulanTerakhir',
            'tahunTerakhir',
            'sudahDibagi',
            'sudahDiproses',
            'bulanLalu',
            'sudah12Bulan',
            'bulanBelumDiproses',
            'acuan',
            'debitProker',
            'daftarAkun',
            'akunDipilih',
        ));
    }


    public function prosesPembagianBulanan(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $existingLog = LogPembagianBulanan::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if ($existingLog) {
            return redirect()->back()->with('error', 'Pembagian untuk bulan ini sudah pernah dilakukan.');
        }

        // Step 1: Ambil total pemasukan (kredit)
        $totalKredit = DB::table('keuangan')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->where('tipe', 'kredit')
            ->sum('jumlah');

        if ($totalKredit <= 0) {
            return redirect()->back()->with('error', 'Tidak ada pemasukan di bulan tersebut.');
        }

        // Step 2: Ambil acuan pembagian
        $acuanPembagian = AcuanPembagian::with('akun')->get();

        // Step 3: Tanggal pembagian
        $tanggalPembagian = Carbon::create($tahun, $bulan, 1)->addMonth()->startOfMonth();

        // Step 4: Ambil saldo terakhir dari entri keuangan paling akhir sebelum tanggal pembagian
        $totalSaldoTersedia = KeuanganHelper::getSaldoAkhirTerakhir($tanggalPembagian);

        if ($totalSaldoTersedia <= 0) {
            return redirect()->back()->with('error', 'Saldo akhir kosong. Tidak bisa melakukan pembagian.');
        }

        $saldoBerjalan = $totalSaldoTersedia;
        $dataPembagian = collect();

        foreach ($acuanPembagian as $item) {
            $jumlahPembagian = $totalSaldoTersedia * ($item->persentase / 100);

            $isBelanja = $item->kategori === 'Belanja Rutin Gereja';
            $tipe = $isBelanja ? 'kredit' : 'debit';
            $keterangan = $isBelanja
                ? 'Alokasi Dana Belanja Gereja'
                : "Pembagian {$item->kategori}";

            $kodePrefix = strtoupper(str_replace(' ', '', substr($item->kategori, 0, 8)));

            $saldo_awal = $saldoBerjalan;

            if ($isBelanja) {
                // saldo akhir sama saldo awal, saldo berjalan juga tetap sama
                $saldo_akhir = $saldo_awal;
            } else {
                $saldo_akhir = ($tipe === 'debit')
                    ? $saldo_awal - $jumlahPembagian
                    : $saldo_awal + $jumlahPembagian;

                $saldoBerjalan = $saldo_akhir; // update saldo berjalan cuma kalau bukan Belanja
            }
            $dataPembagian->push([
                'akun_id' => $item->akun ? $item->akun->id : null,
                'kode_keuangan' => "{$kodePrefix}-{$bulan}",
                'tanggal' => $tanggalPembagian,
                'tipe' => $tipe,
                'jumlah' => $jumlahPembagian,
                'keterangan' => $keterangan,
                'saldo_awal' => $saldo_awal,
                'saldo_akhir' => $saldo_akhir,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 5: Simpan semua entri pembagian ke keuangan
        DB::table('keuangan')->insert($dataPembagian->toArray());

        // Step 6: Simpan ke program_kerja_budget (khusus kategori "Program Kerja")
        $alokasiProgramKerja = $dataPembagian
            ->where('keterangan', 'Pembagian Program Kerja')
            ->sum('jumlah');

        $targetTahun = $tanggalPembagian->year + 1;

        $existing = DB::table('program_kerja_budget')
            ->where('tahun_alokasi', $targetTahun)
            ->first();

        if ($existing) {
            $totalAlokasi = $existing->alokasi_tahun_depan + $alokasiProgramKerja;

            DB::table('program_kerja_budget')
                ->where('tahun_alokasi', $targetTahun)
                ->update(['alokasi_tahun_depan' => $totalAlokasi]);
        } else {
            DB::table('program_kerja_budget')->insert([
                'tahun_alokasi' => $targetTahun,
                'alokasi_tahun_depan' => $alokasiProgramKerja,
            ]);
        }

        // Step 7: Simpan log pembagian
        LogPembagianBulanan::create([
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal_pembagian' => now(),
            'status' => 'selesai',
            'dibuat_pada' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembagian bulanan berhasil diproses!');
    }

    public function addDaftarKeuanganA()
    {
        return view('admin.add-daftarkeuangan');
    }

    public function postDaftarKeuangan(Request $request)
    {
        $request->validate([
            'akun_id' => 'required|exists:akun,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|string',
            'tipe' => 'required|in:kredit,debit',
            'keterangan' => 'required|string|max:255',
        ]);

        $jumlah = preg_replace('/\D/', '', $request->jumlah);
        $jumlah = is_numeric($jumlah) ? (int) $jumlah : 0;

        $tanggal = $request->tanggal;
        $akunId = $request->akun_id;
        $tahun = date('Y', strtotime($tanggal));
        $tipe = $request->tipe;

        // Ambil saldo akhir terakhir sebelum tanggal transaksi baru
        $saldoAwal = KeuanganHelper::getSaldoAkhirTerakhir($tanggal);

        // Hitung saldo akhir berdasarkan tipe transaksi
        if ($tipe === 'kredit') {
            $saldoAkhir = $saldoAwal + $jumlah;
        } else { // debit
            $saldoAkhir = $saldoAwal - $jumlah;
        }

        $lastKode = Keuangan::whereYear('tanggal', $tahun)
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(kode_keuangan, '-', -1) AS UNSIGNED)) as max_kode")
            ->first()
            ->max_kode;

        $nextNumber = $lastKode ? $lastKode + 1 : 1;

        // Format: AKUN-2025-01, AKUN-2025-02, dst.
        $kode_keuangan = 'AKUN-' . $tahun . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        Keuangan::create([
            'akun_id' => $akunId,
            'kode_keuangan' => $kode_keuangan,
            'tanggal' => $tanggal,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'saldo_awal' => $saldoAwal,
            'saldo_akhir' => $saldoAkhir,
            'keterangan' => $request->keterangan,
            'waktu_input' => now(),
        ]);

        return redirect('/daftarKeuangan')->with('success', 'Keuangan berhasil ditambahkan!');
    }


    // public function downloadKeuangan()
    // {
    //     return Excel::download(new KeuanganExport, 'laporan_keuangan.xlsx');
    // }


    public function aproveKeuangan()
    {
        //=========================arsipkan keuangan=================================//
        $tahun = now()->year; // agar tahun otomatis sesuai tahun di submit
        // $tahun = 2024; // ini untuk Ganti tahun sesuai kebutuhan (misal: 2024)
        $namaTabel = 'keuangan_' . $tahun;

        // Cek apakah tabel sudah ada
        if (!Schema::hasTable($namaTabel)) {
            Schema::create($namaTabel, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('akun_id')->nullable();
                $table->foreign('akun_id')->references('id')->on('akun')->onDelete('set null');
                $table->string('kode_keuangan');
                $table->date('tanggal');
                $table->enum('tipe', ['kredit', 'debit']);
                $table->integer('jumlah');
                $table->string('keterangan')->nullable();
                $table->integer('saldo_awal');
                $table->integer('saldo_akhir');
                $table->timestamps();
            });
        }

        // Pindahkan data dari tabel `keuangan` ke tabel baru
        $dataKeuangan = DB::table('keuangan')->get();
        // dd($dataKeuangan);
        foreach ($dataKeuangan as $data) {
            DB::table($namaTabel)->insert([
                'akun_id' => $data->akun_id,
                'kode_keuangan' => $data->kode_keuangan,
                'tanggal' => $data->tanggal,
                'tipe' => $data->tipe,
                'jumlah' => $data->jumlah,
                'keterangan' => $data->keterangan,
                'saldo_awal' => $data->saldo_awal,
                'saldo_akhir' => $data->saldo_akhir,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::table('keuangan')->truncate();

        //=========================arsipkan proker=================================//

        $namaTabelProker = 'program_kerja_' . $tahun;


        if (!Schema::hasTable($namaTabelProker)) {
            Schema::create($namaTabelProker, function (Blueprint $table) {
                $table->id();
                $table->string('kode_program_kerja');
                $table->string('nama_program_kerja');
                $table->string('komisi_program_kerja');
                $table->string('nama_ketua_program_kerja');
                $table->string('nama_majelis_pendamping');
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->text('keterangan')->nullable();
                $table->integer('anggaran_digunakan')->nullable();
                $table->integer('tambahan_dana_kebijakan')->nullable();
                $table->enum('status', ['aktif', 'diarsipkan']);
                $table->integer('tahun');
                $table->timestamps();
            });
        }

        $prokerAktif = DB::table('program_kerja')->where('tahun', $tahun)->get();

        foreach ($prokerAktif as $proker) {
            DB::table($namaTabelProker)->insert([
                'kode_program_kerja' => $proker->kode_program_kerja,
                'nama_program_kerja' => $proker->nama_program_kerja,
                'komisi_program_kerja' => $proker->komisi_program_kerja,
                'nama_ketua_program_kerja' => $proker->nama_ketua_program_kerja,
                'nama_majelis_pendamping' => $proker->nama_majelis_pendamping,
                'tanggal_mulai' => $proker->tanggal_mulai,
                'tanggal_selesai' => $proker->tanggal_selesai,
                'keterangan' => $proker->keterangan,
                'anggaran_digunakan' => $proker->anggaran_digunakan,
                'tambahan_dana_kebijakan' => $proker->tambahan_dana_kebijakan,
                'status' => 'diarsipkan',
                'tahun' => $proker->tahun,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::table('program_kerja')->truncate();

        //=========================arsipkan budget dan alokasi=================================//
        $tahunIni = date('Y');

        $programBudget = ProgramKerjaBudget::where('tahun_alokasi', $tahun + 1)->first();

        if ($programBudget && $programBudget->alokasi_tahun_depan > 0) {
            $programBudget->budget_berjalan += $programBudget->alokasi_tahun_depan;
            $programBudget->alokasi_tahun_depan = 0;

            $programBudget->tahun_budget = $tahun += 1;
            $programBudget->tahun_alokasi = $tahun += 1;
            $programBudget->save();
            //sebenanrya ini bisa otomatis tahunnya, untuk keperluan testing dan ujian makanya dibuat agar punya tahun yang sama dengan budget berjalan
        }

        //=========================arsipkan akun=================================//

        // $namaTabelAkun = 'akun_' . $tahun;

        // // Cek apakah tabel akun_{tahun} sudah ada
        // if (!Schema::hasTable($namaTabelAkun)) {
        //     Schema::create($namaTabelAkun, function (Blueprint $table) {
        //         $table->id();
        //         $table->string('kode_akun');
        //         $table->string('nama_akun');
        //         $table->string('tipe_akun');
        //         $table->text('keterangan')->nullable();
        //         $table->timestamps();
        //     });
        // }

        // // Ambil data dari tabel akun
        // $dataAkun = DB::table('akun')->get();

        // // Insert data akun ke tabel arsip akun_{tahun}
        // foreach ($dataAkun as $akun) {
        //     DB::table($namaTabelAkun)->insert([
        //         'kode_akun' => $akun->kode_akun,
        //         'nama_akun' => $akun->nama_akun,
        //         'tipe_akun' => $akun->tipe_akun,
        //         'keterangan' => $akun->keterangan,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
        // DB::table('akun')->truncate();

        DB::table('log_pembagian_bulanan')->truncate();


        return redirect('/daftarKeuangan')->with('success', 'Data keuangan sudah diarsipkan! dan budget berjalan sudah diperbarui.');
    }

    public function showDaftarKeuangan()
    {

        return view('admin.daftarKeuangan', compact('sudah12Bulan'));
    }
}
