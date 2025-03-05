<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use App\Exports\KeuanganExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboardProkerA()
    {
        return view('admin.proker');
    }

    public function dashboardKeuanganA()
    {
        $keuangan = Keuangan::all();
        $totalKeseluruhan = Keuangan::sum('total_penerimaan');
        return view('admin.keuangan', compact('keuangan', 'totalKeseluruhan'));
    }

    public function daftarProkerA()
    {
        return view('admin.daftarproker');
    }

    public function daftarKeuanganA()
    {
        $keuangan = Keuangan::all();
        $totalKeseluruhan = Keuangan::sum('total_penerimaan');
        return view('admin.daftarkeuangan', compact('keuangan', 'totalKeseluruhan'));
    }

    public function addDaftarKeuanganA()
    {
        return view('admin.add-daftarkeuangan');
    }

    public function postDaftarKeuangan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
            'konveksional' => 'required|string',
            'inkonveksional' => 'required|string',
        ]);

        $konveksional = preg_replace('/\D/', '', $request->konveksional); // Hapus semua kecuali angka
        $inkonveksional = preg_replace('/\D/', '', $request->inkonveksional);

        $konveksional = is_numeric($konveksional) ? (int) $konveksional : 0;
        $inkonveksional = is_numeric($inkonveksional) ? (int) $inkonveksional : 0;


        // Generate kode_keuangan otomatis (misal: "AKUN01", "AKUN02", dst)
        $lastRecord = Keuangan::latest('kode_keuangan')->first();
        $nextNumber = $lastRecord ? intval(substr($lastRecord->kode_keuangan, 4)) + 1 : 1;
        $kode_keuangan = 'AKUN' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        Keuangan::create([
            'kode_keuangan' => $kode_keuangan,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'konveksional' => $konveksional,
            'inkonveksional' => $inkonveksional,
            'total_penerimaan' => $konveksional + $inkonveksional,
            'waktu_input' => now(),
        ]);

        return redirect('/daftarKeuangan')->with('success', 'Keuangan berhasil ditambahkan!');
    }
    
    public function downloadKeuangan()
    {
        return Excel::download(new KeuanganExport, 'laporan_keuangan.xlsx');
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
}
