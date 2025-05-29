<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganDinamis extends Model
{
    protected $table; // kita set secara dinamis di controller
    public $timestamps = false; // sesuaikan kalau tabelmu tidak pakai timestamps

    // Tambahan: jika tabelmu tidak pakai primary key 'id'
    // protected $primaryKey = 'kode_keuangan'; // jika pakai kode sendiri
    // public $incrementing = false; // kalau tidak auto-increment
}
