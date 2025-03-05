<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Exports\KeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

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
    Route::get('/daftarKeuangan', [AdminController::class, 'daftarKeuanganA']);
    Route::get('/daftarUser', [AdminController::class, 'daftarUser']);

    //untuk tambah user
    // Route::get('/addDaftarUser', [AdminController::class, 'addDaftarUser']);
    Route::post('/postDaftarUser', [AdminController::class, 'postDaftarUser'])->name('postDaftarUser');
    //untuk update user
    Route::get('/editUser/{id}', [AdminController::class, 'editUser']);
    Route::get('/admin/user/{id}', [AdminController::class, 'cariUser']);
    Route::put('/updateUser/{id}', [AdminController::class, 'updateUser'])->name('updateUser'); 
    //untuk delete user
    Route::get('/deleteUser/{id}', [AdminController::class, 'deleteUser']);

    //untuk tambah keuangan
    // Route::get('/addKeuangan', [AdminController::class, 'addDaftarKeuanganA']);
    Route::post('/postDaftarKeuangan', [AdminController::class, 'postDaftarKeuangan'])->name('postDaftarKeuangan');

    Route::get('/download-keuangan', [AdminController::class, 'downloadKeuangan'])->name('download.keuangan');

});
//dashboard
Route::get('/prokerAdmin', [AdminController::class, 'dashboardProkerA']);
Route::get('/keuanganAdmin', [AdminController::class, 'dashboardKeuanganA']);
//daftar kerja
Route::get('/daftarProker', [AdminController::class, 'daftarProkerA']);
Route::get('/daftarKeuangan', [AdminController::class, 'daftarKeuanganA']);



//user
Route::get('/daftarUser', [AdminController::class, 'daftarUser']);

Route::get('/dashboardUser', [UserController::class, 'dashboardUser']);