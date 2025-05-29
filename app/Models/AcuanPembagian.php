<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcuanPembagian extends Model
{
    use HasFactory;
    protected $table = 'acuan_pembagian';
    protected $fillable = [
        'kategori',
        'persentase',
    ];
    protected $casts = [
        'persentase' => 'decimal:2',
    ];
    protected $attributes = [
        'persentase' => 0,
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class,'akun_id');
    }
}
