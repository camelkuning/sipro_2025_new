<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users'; 

    protected $fillable = [
        'id',
        'nama_lengkap',
        'komisi',
        'role',
        'username',
        'password',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];
}
