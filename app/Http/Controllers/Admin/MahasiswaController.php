<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MasterMahasiswa;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPeminjaman;
use App\Http\Controllers\Controller;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = MasterMahasiswa::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('jenis_kelamin', function($row) {
                if ($row->jenis_kelamin === 'L') return 'Laki-laki';
                if ($row->jenis_kelamin === 'P') return 'Perempuan';
                return $row->jenis_kelamin;
            })
            ->editColumn('flag_aktif', function($row) {
                if ($row->flag_aktif === 'Y') return 'Aktif';
                if ($row->flag_aktif === 'N') return 'Nonaktif';
                return $row->flag_aktif;
            })
            ->addColumn('action', function($row) {
                $statusBtn = $row->flag_aktif === 'Y' 
                    ? '<button class="btn btn-sm btn-warning toggleAktifBtn" data-id="'.$row->id_user.'">Deactive</button>'
                    : '<button class="btn btn-sm btn-success toggleAktifBtn" data-id="'.$row->id_user.'">Active</button>';

                $deleteBtn = '<button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id_user.'">Delete</button>';

                return $statusBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['action'])
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
        $mahasiswa = MasterMahasiswa::findOrFail($id);

        if ($request->has('toggle_aktif')) {
            $mahasiswa->flag_aktif = $mahasiswa->flag_aktif === 'Y' ? 'N' : 'Y';
        }   

        $mahasiswa->save();

        $status = ($mahasiswa->flag_aktif=='Y') ? 'Aktif' : 'Nonaktif';
        return response()->json([
            'message' => 'Mahasiswa berhasil diperbarui',
            'flag_aktif' => $status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $mahasiswa = MasterMahasiswa::findOrFail($id);
            $user = User::findOrFail($mahasiswa->id_user);
            TransaksiPeminjaman::where('id_user', $mahasiswa->id_user)->delete();

            $mahasiswa->delete();
            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'Data Mahasiswa berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal menghapus data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
