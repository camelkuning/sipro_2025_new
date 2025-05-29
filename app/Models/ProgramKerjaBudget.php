<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProgramKerjaBudget extends Model
{
    protected $table = 'program_kerja_budget'; // pastikan nama tabel sesuai

    protected $fillable = [
        'tahun_alokasi',
        'alokasi_tahun_depan',
        'tahun_budget',
        'budget_berjalan',

    ];

    public $timestamps = false; // matikan timestamps kalo tabelnya tidak pakai created_at & updated_at

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id'); // sesuaikan nama foreign key jika beda
    }
}
