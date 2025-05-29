<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $table = 'program_kerja';

    protected $primaryKey = 'kode_program_kerja';
    public $incrementing = false; // karena kode_program_kerja bukan auto increment
    protected $keyType = 'string'; // karena kode_program_kerja adalah string

    protected $fillable = [
        'id',
        'akun_id',
        'kode_program_kerja',
        'nama_program_kerja',
        'komisi_program_kerja',
        'nama_ketua_program_kerja',
        'nama_majelis_pendamping',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'anggaran_digunakan',
        'tambahan_dana_kebijakan',
        'tahun',
        'status'
    ];
    // accessor untuk hitung total budget
    public function getTotalBudgetAttribute()
    {
        return $this->anggaran_digunakan + $this->tambahan_dana_kebijakan;
    }

    // scope untuk hanya program kerja aktif
    // public function scopeAktif($query)
    // {
    //     return $query->where('status', 'aktif');
    // }

    // scope untuk tahun tertentu
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }


    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id', 'id');
    }

}
