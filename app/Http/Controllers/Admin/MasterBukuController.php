<?php
namespace App\Http\Controllers\Admin;

use App\Models\StockBuku;
use App\Models\MasterBuku;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MasterBukuController extends Controller
{
    public function index()
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

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '<button class="btn btn-primary btn-sm edit" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="'.$row->id.'">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $buku = DB::table('MasterBuku')
            ->leftJoin('StokBuku', 'MasterBuku.id', '=', 'StokBuku.id_buku')
            ->select('MasterBuku.*', 'StokBuku.jumlah_stok')
            ->where('MasterBuku.id', $id)
            ->get();
        return response()->json($buku);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'pengarang' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'isbn' => 'required|string',
            'jumlah_stok' => 'nullable|integer|min:0',
        ]);

        $buku = MasterBuku::create($validated);

        StockBuku::create([
            'id_buku' => $buku->id,
            'jumlah_stok' => $validated['jumlah_stok'] ?? 0,
        ]);

        return response()->json($buku);
    }

    public function update(Request $request, $id)
    {
        $buku = MasterBuku::find($id);
        $buku->update($request->all());

        if ($request->has('jumlah_stok')) {
            $stok = StockBuku::where('id_buku', $buku->id)->first();
    
            if ($stok) {
                $stok->jumlah_stok = $request->jumlah_stok;
                $stok->save();
            } else {
                StockBuku::create([
                    'id_buku' => $buku->id,
                    'jumlah_stok' => $request->jumlah_stok
                ]);
            }
        }

        return response()->json($buku);
    }

    public function destroy($id)
    {
        MasterBuku::findOrFail($id)->delete();

        StockBuku::where('id_buku', $id)->delete();

        return response()->json(['success' => true]);
    }
}
