<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Akun extends Model
{
    use HasFactory;
    protected $table = 'akun';
    protected $fillable = [
        'id',
        'kode_akun',
        'nama_akun',
        'tipe_akun',
        'keterangan',
    ];

    public function keuangans()
    {
        return $this->hasMany(Keuangan::class);
    }

    public function programKerjas()
    {
        return $this->hasMany(ProgramKerja::class);
    }
}
