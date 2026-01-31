<?php

namespace App\Http\Controllers\Siswa;

use App\Models\StockBuku;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HistoryPeminjaman;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPeminjaman;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'id_user' => 'required|integer',
            'id_buku' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $stok = StockBuku::where('id_buku', $request->id_buku)
                ->lockForUpdate()
                ->first();

                if (!$stok || $stok->jumlah_stok < 1) {
                    return response()->json(['error' => 'Stok buku tidak cukup'], 422);
                }
                $jumlah = $stok->jumlah_stok - 1;
                $stok->jumlah_stok = $jumlah;
                $stok->save();

                TransaksiPeminjaman::create([
                    'id_user' => $request->id_user,
                    'id_buku' => $request->id_buku,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $request->tanggal_kembali,
                    'flag_end' => 'N',
                    'created_at' => now(),
                    'updated_at' => null,
                ]);
            });
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true]);

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

    public function kembalikanBuku(Request $request)
    {
        $request->validate([
            'id_buku' => 'required'
        ]);

        DB::enableQueryLog();
        DB::transaction(function () use ($request) {
            $userId = auth()->user()->id;
            $idBuku = $request->id_buku;

            $trx = TransaksiPeminjaman::where('id_user', $userId)
                ->where('id_buku', $idBuku)
                ->where('flag_end', 'N')
                ->lockForUpdate()
                ->first();

            if (!$trx) {
                return response()->json(['error' => 'Transaksi tidak ditemukan atau sudah dikembalikan'], 422);
            }

            HistoryPeminjaman::create([
                'id_user' => $trx->id_user,
                'id_buku' => $trx->id_buku,
                'tanggal_pinjam' => $trx->tanggal_pinjam,
                'tanggal_kembali' => $trx->tanggal_kembali,
                'flag_end' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $updated = TransaksiPeminjaman::where('id_user', $userId)
                ->where('id_buku', $idBuku)
                ->where('flag_end', 'N')
                ->update([
                    'flag_end' => 'Y',
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return response()->json(['error' => 'Gagal update transaksi'], 422);
            }

            $stok = StockBuku::find($idBuku);
            if ($stok) {
                $stok->jumlah_stok += 1;
                $stok->save();
            }
        });

        $queries = DB::getQueryLog();
        foreach ($queries as $q) {
            $sql = Str::replaceArray('?', $q['bindings'], $q['query']);
            Log::info($sql);
        }

        return response()->json(['success' => true]);
    }

}
