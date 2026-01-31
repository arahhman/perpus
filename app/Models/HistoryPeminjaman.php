<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPeminjaman extends Model
{
    use HasFactory;
    protected $table = 'HistoryPeminjaman';
    protected $fillable = [
        'id_user',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'flag_end',
        'created_at',
        'updated_at'
    ];
}
