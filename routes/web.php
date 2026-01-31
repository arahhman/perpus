<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/app');
    }
    return redirect('/login');
});

Route::get('/app', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    return auth()->user()->role === 'admin'
        ? redirect('/app/admin')
        : redirect('/app/siswa');
});

Route::middleware('auth')->group(function () {
    Route::get('/app/admin/{any?}', function () {
        return view('app');
    })->where('any', '.*');

    Route::get('/app/siswa/{any?}', function () {
        return view('app');
    })->where('any', '.*');

});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/get-dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('get-dashboard');
    Route::apiResources([
        'master-buku' => App\Http\Controllers\Admin\MasterBukuController::class,
        'mahasiswa' => App\Http\Controllers\Admin\MahasiswaController::class,
        'peminjaman' => App\Http\Controllers\Admin\PeminjamanController::class,
        'laporan-peminjaman' => App\Http\Controllers\Admin\LaporanPeminjamanController::class
    ]);
});

Route::prefix('siswa')->middleware(['auth'])->group(function () {
    Route::get('/get-dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('get-dashboard');
    Route::apiResources([
        'mahasiswa' => App\Http\Controllers\Siswa\MahasiswaController::class,
        'peminjaman' => App\Http\Controllers\Siswa\PeminjamanController::class,
        'laporan-peminjaman' => App\Http\Controllers\Siswa\LaporanPeminjamanController::class
    ]);
    Route::post('/kembalikan-buku', [App\Http\Controllers\Siswa\PeminjamanController::class, 'kembalikanBuku'])->name('kembalikan-buku');
});

Route::get('/list-master/buku', [App\Http\Controllers\ListMasterController::class, 'buku'])->name('list-master.buku');
Route::get('/list-master/bukuAll', [App\Http\Controllers\ListMasterController::class, 'bukuAll'])->name('list-master.bukuAll');
Route::get('/list-master/mahasiswa', [App\Http\Controllers\ListMasterController::class, 'mahasiswa'])->name('list-master.mahasiswa');
