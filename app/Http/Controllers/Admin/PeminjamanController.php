<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockBuku;
use Illuminate\Http\Request;
use App\Models\MasterMahasiswa;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPeminjaman;
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
            $mahasiswa = MasterMahasiswa::where('id_user', $request->id_user)->first();

            if (!$mahasiswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 422);
            }
            if ($mahasiswa->flag_aktif != 'Y') {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak aktif, tidak bisa meminjam buku'
                ], 422);
            }

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
                    'created_at' => now(),
                    'updated_at' => null,
                    'flag_end' => 'N',
                ]);
            });
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil disimpan'
        ]);
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
