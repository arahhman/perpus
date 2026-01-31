<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPeminjaman extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'TransaksiPeminjaman';
    protected $fillable = [
        'id',
        'id_user',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'flag_end',
        'created_at',
        'updated_at'
    ];
}
