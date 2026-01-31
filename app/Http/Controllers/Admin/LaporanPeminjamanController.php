<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPeminjaman;
use App\Http\Controllers\Controller;

class LaporanPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request )
    {
        // DB::enableQueryLog();

        $query = DB::table('TransaksiPeminjaman as a')
            ->leftJoin('MasterBuku as b', 'a.id_buku', '=', 'b.id')
            ->leftJoin('MasterMahasiswa as c', 'a.id_user', '=', 'c.id_user')
            ->select(
                DB::raw("concat(c.nama, ' (', c.nim, ')') as mahasiswa"),
                DB::raw("concat(b.judul, ' (', a.id_buku, ')') as buku"),
                'a.tanggal_pinjam',
                'a.tanggal_kembali',
                DB::raw("concat(DATEDIFF(a.tanggal_kembali, a.tanggal_pinjam), ' hari') as lamappinjam"),
                DB::raw("case when a.flag_end = 'Y' then 'Selesai' else 'Dipinjam' end as statusbuku")
            );

        if ($request->nim) {
            $query->where('c.nim', $request->nim);
        }
        if ($request->id_buku) {
            $query->where('b.id', $request->id_buku);
        }
        if ($request->tanggal_pinjam) {
            $query->whereDate('a.tanggal_pinjam', $request->tanggal_pinjam);
        }
        if ($request->tanggal_kembali) {
            $query->whereDate('a.tanggal_kembali', $request->tanggal_kembali);
        }
        if ($request->lama) {
            $query->whereRaw('DATEDIFF(a.tanggal_kembali, a.tanggal_pinjam) = ?', [$request->lama]);
        }

        // $data = $query->get();
        // $queries = DB::getQueryLog();
        // foreach ($queries as $q) {
        //     $sql = Str::replaceArray('?', $q['bindings'], $q['query']);
        //     Log::info($sql);
        // }

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
