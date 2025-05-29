<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKerjaPengeluaran extends Model
{
    use HasFactory;
    protected $table = 'program_kerja_pengeluaran_table';
    protected $fillable = [
        'program_kerja_id',
        'tanggal',
        'keterangan',
        'jumlah',
    ];
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'program_kerja_id');
    }
}
