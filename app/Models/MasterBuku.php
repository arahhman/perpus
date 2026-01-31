<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBuku extends Model
{
    use HasFactory;
    protected $table = 'MasterBuku';
    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn'
    ];
}
