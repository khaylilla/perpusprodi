<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'nama',
        'npm',
        'judul_buku',
        'nomor_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'hari_terlambat',
        'total_denda',
    ];
}
