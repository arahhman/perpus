<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBuku extends Model
{
    use HasFactory;

    protected $table = 'StokBuku';

    protected $primaryKey = 'id_buku';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id_buku',
        'jumlah_stok'
    ];

    public $timestamps = true;
}
