<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = ['nama', 'nip', 'jabatan', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];
}
