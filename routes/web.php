<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PenilaianPegawaiController;
use App\Http\Controllers\SosmedController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::middleware('auth')->group(function(){
//     Route::get('/',[SosmedController::class,'admin'])->name('admin');
// Route::post('export-youtube',[SosmedController::class,'exportYoutube'])->name('exportYoutube');
// Route::post('export-instagram',[SosmedController::class,'exportInstagram'])->name('exportInstagram');

// Route::get('/logout',[AuthController::class,'logout'])->name('logout');
// });

Route::middleware('auth')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('input-pallet', [StokMasukController::class, 'inputPallet'])->name('inputPallet');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('add-session-gudang/{gudang_id}', [MenuController::class, 'addSessionGudang'])->name('addSessionGudang');



    Route::middleware('hakakses:1')->group(function () {


        Route::get('user', [UserController::class, 'index'])->name('user');
        Route::get('get-data-user', [UserController::class, 'getDataUser'])->name('getDataUser');
        Route::post('edit-user', [UserController::class, 'editUser'])->name('editUser');
        Route::post('add-user', [UserController::class, 'addUser'])->name('addUser');

        Route::get('barang', [BarangController::class, 'index'])->name('barang');
        Route::post('add-barang', [BarangController::class, 'addBarang'])->name('addBarang');
        Route::post('edit-barang', [BarangController::class, 'editBarang'])->name('editBarang');
    });


    //dashboard
    Route::get('data-block', [DashboardController::class, 'dataBlock'])->name('dataBlock');
    Route::get('detail-block/{id}', [DashboardController::class, 'detailBlock'])->name('detailBlock');
    Route::get('detail-cell/{id}', [DashboardController::class, 'detailCell'])->name('detailCell');
    Route::get('detail-rak/{id}', [DashboardController::class, 'detailRak'])->name('detailRak');
    Route::get('data-stok', [DashboardController::class, 'dataStok'])->name('dataStok');

    Route::get('generate-qr/{cell_id}', [DashboardController::class, 'generateQr'])->name('generateQr');

    Route::get('getPindahBarang/{pallet_id}/{tgl_exp}/{barang_id}/{block_id}/{cell_id}/{rak_id}', [DashboardController::class, 'getPindahBarang'])->name('getPindahBarang');
    Route::post('pindahBarang', [DashboardController::class, 'pindahBarang'])->name('pindahBarang');
    //end dashboard

    //returnBarang
    Route::get('return-barang', [DashboardController::class, 'returnBarang'])->name('returnBarang');
    Route::post('add-cart-return-barang', [DashboardController::class, 'addCartReturnBarang'])->name('addCartReturnBarang');
    Route::get('get-cart-return-barang', [DashboardController::class, 'getCartReturnBarang'])->name('getCartReturnBarang');
    Route::get('delete-cart-return-barang/{id}', [DashboardController::class, 'deleteCartReturnBarang'])->name('deleteCartReturnBarang');
    Route::post('save-return-barang', [DashboardController::class, 'saveReturnBarang'])->name('saveReturnBarang');

    Route::get('list-stok-hold', [DashboardController::class, 'listStokHold'])->name('listStokHold');
    Route::get('delete-stok-hold/{id}', [DashboardController::class, 'deleteStokHold'])->name('deleteStokHold');
    //end ReturnBarang

    Route::get('input-stok-masuk', [StokMasukController::class, 'inputStokMasuk'])->name('inputStokMasuk');
    Route::get('get-cell/{block_id}', [StokMasukController::class, 'getCell'])->name('getCell');
    Route::get('get-rak/{cell_id}', [StokMasukController::class, 'getRak'])->name('getRak');
    Route::get('get-pallet/{rak_id}', [StokMasukController::class, 'getPallet'])->name('getPallet');
    Route::post('add-cart-masuk', [StokMasukController::class, 'addCartMasuk'])->name('addCartMasuk');
    Route::get('get-cart-masuk', [StokMasukController::class, 'getCartMasuk'])->name('getCartMasuk');
    Route::get('delete-cart-masuk/{id}', [StokMasukController::class, 'deleteCartMasuk'])->name('deleteCartMasuk');
    Route::get('save-stok-masuk', [StokMasukController::class, 'saveStokMasuk'])->name('saveStokMasuk');
    Route::post('import-stok-masuk', [StokMasukController::class, 'importStokMasuk'])->name('importStokMasuk');
    Route::get('download-format', [StokMasukController::class, 'downloadFormat'])->name('downloadFormat');
    Route::get('get-stok-masuk/{stok_id}', [StokMasukController::class, 'getStokMasuk'])->name('getStokMasuk');
    Route::post('edit-stok-masuk', [StokMasukController::class, 'editStokMasuk'])->name('editStokMasuk');

    Route::get('list-stok-masuk', [StokMasukController::class, 'listStokMasuk'])->name('listStokMasuk');
    Route::get('delete-stok-masuk/{id}', [StokMasukController::class, 'deleteStokMasuk'])->name('deleteStokMasuk');

    Route::get('get-data-pallet/{pallet_id}', [StokMasukController::class, 'getDataPallet'])->name('getDataPallet');

    Route::get('checkBlockWh/{block_id}', [StokMasukController::class, 'checkBlockWh'])->name('checkBlockWh');

    //checker
    Route::get('checker-masuk', [StokMasukController::class, 'checkerMasuk'])->name('checkerMasuk');
    Route::get('detail-masuk/{kd_gabungan}', [StokMasukController::class, 'detailMasuk'])->name('detailMasuk');
    Route::get('pdf-detail-masuk/{kd_gabungan}', [StokMasukController::class, 'pdfDetailMasuk'])->name('pdfDetailMasuk');
    Route::post('addChecker', [StokMasukController::class, 'addChecker'])->name('addChecker');
    //endchecker

    //mitra
    Route::get('mitra', [MitraController::class, 'index'])->name('mitra');
    Route::post('add-mitra', [MitraController::class, 'addMitra'])->name('addMitra');
    Route::post('edit-mitra', [MitraController::class, 'editMitra'])->name('editMitra');
    //endMitra

    //stok keluar
    Route::get('input-stok-keluar', [StokKeluarController::class, 'inputStokKeluar'])->name('inputStokKeluar');
    Route::get('get-detail-barang/{barang_id}', [StokKeluarController::class, 'getDetailBarang'])->name('getDetailBarang');
    Route::post('add-cart-keluar', [StokKeluarController::class, 'addCartKeluar'])->name('addCartKeluar');
    Route::get('get-cart-keluar', [StokKeluarController::class, 'getCartKeluar'])->name('getCartKeluar');
    Route::get('delete-cart-keluar/{id}', [StokKeluarController::class, 'deleteCartKeluar'])->name('deleteCartKeluar');
    Route::post('save-stok-keluar', [StokKeluarController::class, 'saveStokKeluar'])->name('saveStokKeluar');
    Route::get('checker-keluar', [StokKeluarController::class, 'checkerKeluar'])->name('checkerKeluar');
    Route::get('detail-keluar/{id}', [StokKeluarController::class, 'detailKeluar'])->name('detailKeluar');
    Route::get('pdf-detail-keluar/{kd_gabungan}', [StokKeluarController::class, 'pdfDetailKeluar'])->name('pdfDetailKeluar');
    Route::post('add-checker-keluar', [StokKeluarController::class, 'addCheckerKeluar'])->name('addCheckerKeluar');

    Route::get('list-stok-keluar', [StokKeluarController::class, 'listStokKeluar'])->name('listStokKeluar');
    Route::get('delete-stok-keluar/{id}', [StokKeluarController::class, 'deleteStokKeluar'])->name('deleteStokKeluar');

    Route::get('getDetailBarangQR/{pallet_id}', [StokKeluarController::class, 'getDetailBarangQR'])->name('getDetailBarangQR');
    //end stok keluar

    //laporan
    Route::get('laporan-stok-masuk', [LaporanController::class, 'laporanStokMasuk'])->name('laporanStokMasuk');
    Route::get('pdf-stok-masuk/{kd_gabungan}', [LaporanController::class, 'pdfStokMasuk'])->name('pdfStokMasuk');

    Route::get('laporan-stok-keluar', [LaporanController::class, 'laporanStokKeluar'])->name('laporanStokKeluar');
    Route::get('pdf-stok-keluar/{kd_gabunagn}', [LaporanController::class, 'pdfStokKeluar'])->name('pdfStokKeluar');

    Route::get('laporan-penerimaan-pengiriman', [LaporanController::class, 'laporanPenerimaanPengiriman'])->name('laporanPenerimaanPengiriman');
    Route::get('pdf-penerimaan-pengiriman', [LaporanController::class, 'pdfPenerimaanPengiriman'])->name('pdfPenerimaanPengiriman');

    Route::get('laporan-stok-kadaluarsa', [LaporanController::class, 'laporanStokKadaluarsa'])->name('laporanStokKadaluarsa');
    Route::get('pdfstok-kadaluarsa', [LaporanController::class, 'pdfStokKadaluarsa'])->name('pdfStokKadaluarsa');
    //end laporan

    //layout
    Route::get('layout', [LayoutController::class, 'index'])->name('layout');
    Route::post('addLayout', [LayoutController::class, 'addLayout'])->name('addLayout');
    //end layout

    //block
    Route::get('forbidden-access', [AuthController::class, 'block'])->name('block');
    //endblock
    Route::get('ganti-password', [UserController::class, 'gantiPassword'])->name('gantiPassword');
    Route::post('edit-password', [UserController::class, 'editPassword'])->name('editPassword');



    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('non-active', [AuthController::class, 'nonActive'])->name('nonActive');
});




Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login_page'])->name('loginPage');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});
