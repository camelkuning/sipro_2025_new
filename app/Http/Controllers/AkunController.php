<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun;

class AkunController extends Controller
{
    public function index()
    {
        $daftarAkun = Akun::withCount(['keuangans', 'programKerjas'])->get();
        return view('akun.index', compact('daftarAkun'));
    }

    public function create()
    {
        return view('akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable',
            'kode_akun' => 'required|unique:akun',
            'nama_akun' => 'required',
            'tipe_akun' => 'required',
            'keterangan' => 'nullable',
        ]);
        

        Akun::create($request->all());
        return redirect()->route('acuan.table-acuan')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function show($id)
    {
        $akun = Akun::with(['keuangans', 'programKerjas'])->findOrFail($id);

        $totalKredit = $akun->keuangans()->where('tipe', 'kredit')->sum('jumlah');
        $totalDebit  = $akun->keuangans()->where('tipe', 'debit')->sum('jumlah');
        $saldo       = $totalKredit - $totalDebit;
        return view('akun.show', compact('akun'));
    }
}
