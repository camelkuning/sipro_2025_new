<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramKerjaBudget;
use App\Models\ProgramKerja;
use App\Models\ProgramKerjaPengeluaran;
use App\Models\Akun;

class ProgramKerjaController extends Controller
{
    // Tampilkan semua program kerja
    public function index()
    {
        $programs = ProgramKerja::with('pengeluaran')->latest()->get();
        return view('admin.daftarproker', compact('programs'));
    }

    // Form tambah program kerja
    public function create()
    {
        $akunProgram = Akun::where('kategori', 'Program Kerja')->get();
        return view('admin.daftarproker', compact('akunProgram'));
    }

    // Simpan program kerja baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string',
            'tahun' => 'required|digits:4',
            'akun_id' => 'nullable|exists:akun,id',
        ]);

        ProgramKerja::create([
            'nama_program' => $request->nama_program,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'akun_id' => $request->akun_id,
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.daftarproker')->with('success', 'Program kerja berhasil ditambahkan.');
    }

    // Form edit
    public function edit($id)
    {
        $program = ProgramKerja::findOrFail($id);
        $akunProgram = Akun::where('kategori', 'Program Kerja')->get();
        return view('admin.daftarproker', compact('program', 'akunProgram'));
    }

    // Simpan update
    public function update(Request $request, $id)
    {
        $program = ProgramKerja::findOrFail($id);

        $request->validate([
            'nama_program' => 'required|string',
            'tahun' => 'required|digits:4',
        ]);

        $program->update([
            'nama_program' => $request->nama_program,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'akun_id' => $request->akun_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.daftarproker')->with('success', 'Program kerja berhasil diperbarui.');
    }

    // Hapus program kerja
    public function destroy($id)
    {
        ProgramKerja::findOrFail($id)->delete();
        return redirect()->route('admin.daftarproker')->with('success', 'Program kerja berhasil dihapus.');
    }

    // Tampilkan detail + pengeluaran
    public function show($id)
    {
        $program = ProgramKerja::with('pengeluaran')->findOrFail($id);
        return view('program_kerja.show', compact('program'));
    }

    // Tambah pengeluaran program kerja
    public function storePengeluaran(Request $request, $programId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
        ]);

        ProgramKerjaPengeluaran::create([
            'program_kerja_id' => $programId,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
        ]);

        return back()->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // Hapus pengeluaran
    public function destroyPengeluaran($id)
    {
        ProgramKerjaPengeluaran::findOrFail($id)->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
