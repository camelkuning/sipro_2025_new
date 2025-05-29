<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;
    protected $table = 'keuangan';
    protected $fillable = [
        'id',
        'akun_id',
        'kode_keuangan',
        'tanggal',
        'keterangan',
        'tipe',
        'jumlah',
        'saldo_awal',
        'saldo_akhir',
        'sumber_dana',

    ];

    // protected $attributes = [
    //     'status' => 'aktif',
    // ];

    

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id', 'id');
    }
}
