<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPembagianBulanan extends Model
{
    protected $table = 'log_pembagian_bulanan';

    protected $fillable = [
        'bulan',
        'tahun',
        'tanggal_pembagian',
        'status',
    ];
}
