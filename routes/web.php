<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DaftarUserController;
use App\Http\Controllers\ProgramKerjaController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Exports\KeuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AcuanPembagianController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\ExportController;

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


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/postlogin', [AuthController::class, 'postlogin'])->name('postlogin');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');



Route::middleware('role:admin')->group(function () {
    Route::get('/prokerAdmin', [AdminController::class, 'dashboardProkerA']);
    Route::get('/keuanganAdmin', [AdminController::class, 'dashboardKeuanganA']);
    Route::get('/daftarProker', [AdminController::class, 'daftarProkerA']);
    Route::get('/daftarKeuangan', [AdminController::class, 'daftarKeuanganA'])->name('admin.daftarkeuangan');


    //untuk menu kerja user
    Route::get('/daftarUser', [AdminController::class, 'daftarUser']);
    Route::post('/postDaftarUser', [AdminController::class, 'postDaftarUser'])->name('postDaftarUser');
    Route::get('/editUser/{id}', [AdminController::class, 'editUser']);
    Route::get('/admin/user/{id}', [AdminController::class, 'cariUser']);
    Route::put('/updateUser/{id}', [AdminController::class, 'updateUser'])->name('updateUser');
    Route::get('/deleteUser/{id}', [AdminController::class, 'deleteUser']);

    //untuk tambah keuangan
    Route::post('/postDaftarKeuangan', [AdminController::class, 'postDaftarKeuangan'])->name('postDaftarKeuangan');
    Route::get('/download-keuangan', [AdminController::class, 'downloadKeuangan'])->name('download.keuangan');
    //untuk approve data keuangan
    Route::post('/aproveKeuangan', [AdminController::class, 'aproveKeuangan'])->name('aproveKeuangan');



    Route::get('/acuan-pembagian', [AcuanPembagianController::class, 'index'])->name('acuan.table-acuan');
    Route::post('/acuan-pembagian/update', [AcuanPembagianController::class, 'update'])->name('acuan.update');
    Route::post('/proses-pembagian-bulanan', [AdminController::class, 'prosesPembagianBulanan'])->name('proses.pembagian.bulanan');

    // Route::get('/programKerja', [AdminController::class, 'daftarProkerA'])->name('program_kerja.daftarProker');


    //Menu Program Kerja
    Route::post('/store', [AdminController::class, 'store'])->name('programkerja.store');
    Route::put('/updateproker/{id}', [AdminController::class, 'updateProker'])->name('programkerja.update');
    Route::get('/deleteproker/{id}', [AdminController::class, 'deleteProker'])->name('programkerja.delete');

    Route::post('/update-pengeluaran/{kode_program_kerja}', [AdminController::class, 'updatePengeluaran'])->name('programkera.updatePengeluaran');
    Route::post('/tambah-dana-kebijakan/{kode_program_kerja}', [AdminController::class, 'tambahDanaKebijakan'])->name('programkerja.tambahDanaKebijakan');
    Route::post('/arsipkan/{tahun}', [AdminController::class, 'arsipkanTahun'])->name('programkerja.arsipkan');

    //menu akun
    Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');

   
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chart-data', [AdminController::class, 'getChartData'])->name('chart.data');
    Route::get('/chart-pembagian', [UserController::class, 'getChartPembagian'])->name('chart.pembagian');
    Route::get('/admin/export-keuangan-pdf', [ExportController::class, 'exportKeuanganPDF'])->name('keuangan.exportpdf');
    Route::get('/chart-data-proker', [AdminController::class, 'chartDataProker']);


});


Route::middleware(['role:user'])->group(function () {
    Route::get('/proker', [UserController::class, 'dashboardProkerUser']);
    Route::get('/keuangan', [UserController::class, 'dashboardKeuanganUser'])->name('user.dashboard.keuangan'); ;
    // Route::get('/chart-data', [UserController::class, 'getChartData'])->name('chart.data');
});
