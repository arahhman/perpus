<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMahasiswa extends Model
{
    use HasFactory;
    protected $table = 'MasterMahasiswa';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'id_user',
        'nim',
        'nama',
        'jenis_kelamin',
        'alamat',
        'ttl',
        'program_studi'
    ];
}
