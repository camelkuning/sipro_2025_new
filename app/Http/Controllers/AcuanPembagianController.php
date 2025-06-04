<?php

namespace App\Http\Controllers;

use App\Models\AcuanPembagian;
use App\Models\Akun;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AcuanPembagianController extends Controller
{
    public function index( )
    {
        $tahunIni = date('Y');
        
        $data = AcuanPembagian::all();
        $akun = Akun::all();

        $bulanUnik = DB::table('keuangan')
            ->where('tipe', 'kredit')
            ->whereYear('tanggal', $tahunIni)
            ->selectRaw('MONTH(tanggal) as bulan')
            ->distinct()
            ->pluck('bulan');

        $sudah12Bulan = $bulanUnik->count() === 12;
        return view('acuan.table-acuan', compact('data', 'akun', 'sudah12Bulan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'persentase.*' => 'required|numeric|min:0|max:100'
        ]);

        $total = array_sum($request->persentase);
        if ($total != 100) {
            return back()->withErrors(['msg' => 'Total persentase harus 100%. Sekarang: ' . $total . '%']);
        }

        foreach ($request->persentase as $id => $value) {
            AcuanPembagian::where('id', $id)->update(['persentase' => $value]);
        }

        return back()->with('success', 'Persentase berhasil diperbarui!');
    }
}
