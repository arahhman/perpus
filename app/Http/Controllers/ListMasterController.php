<?php
namespace App\Http\Controllers;

use App\Models\MasterBuku;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ListMasterController extends Controller
{
    public function buku()
    {
        $query = MasterBuku::leftJoin('StokBuku', 'MasterBuku.id', '=', 'StokBuku.id_buku')
            ->select(
                'MasterBuku.id',
                'MasterBuku.judul',
                'MasterBuku.pengarang',
                'MasterBuku.penerbit',
                'MasterBuku.tahun_terbit',
                'MasterBuku.isbn',
                'StokBuku.jumlah_stok'
            );
        if (request()->id_user && request()->id_user != '') {
            $query->whereRaw(
                "MasterBuku.id NOT IN (SELECT id_buku FROM TransaksiPeminjaman WHERE flag_end = 'N' AND id_user = ?)",
                [request()->id_user]
            );
        }

        return response()->json($query->get());
    }

    public function bukuAll()
    {
        $query = MasterBuku::leftJoin('StokBuku', 'MasterBuku.id', '=', 'StokBuku.id_buku')
            ->select(
                'MasterBuku.id',
                'MasterBuku.judul',
                'MasterBuku.pengarang',
                'MasterBuku.penerbit',
                'MasterBuku.tahun_terbit',
                'MasterBuku.isbn',
                'StokBuku.jumlah_stok'
            );

        return response()->json($query->get());
    }
}
