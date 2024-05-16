<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Auth\Verify;
use App\Livewire\Home;
use App\Livewire\HomeJtt;
use App\Livewire\Module\MaklumatWargaKerja;
use App\Livewire\Module\MesyuaratJtt;
use App\Livewire\Module\PegawaiDinilai;
use App\Livewire\Module\PegawaiMenilai;
use App\Livewire\Module\PegawaiPemudahCara;
use App\Livewire\Module\Perakuan;
use App\Livewire\Module\Prestasi\Bulanan;
use App\Livewire\Module\Prestasi\Kumulatif;
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

// Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('login', Login::class)
        ->name('login');

    // Route::get('register', Register::class)
    //     ->name('register');
});

Route::get('password/reset', Email::class)
    ->name('password.request');

Route::get('password/reset/{token}', Reset::class)
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/dashboard-jtt', HomeJtt::class)->name('dashboard-jtt');

    // Route::get('email/verify', Verify::class)
    //     ->middleware('throttle:6,1')
    //     ->name('verification.notice');

    // Route::get('password/confirm', Confirm::class)
    //     ->name('password.confirm');

    // Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
    //     ->middleware('signed')
    //     ->name('verification.verify');

    Route::post('logout', LogoutController::class)
        ->name('logout');

    // maklumat warga kerja
    Route::get('/maklumat-warga-kerja', MaklumatWargaKerja::class)->name('maklumat-warga-kerja');

    // PYD
    Route::get('/pegawai-dinilai', PegawaiDinilai::class)->name('pegawai-dinilai');

    // PYM
    Route::get('/pegawai-menilai', PegawaiMenilai::class)->name('pegawai-menilai');

    // PMC
    Route::get('/pegawai-pemudah-cara', PegawaiPemudahCara::class)->name('pegawai-pemudah-cara');

    // perakuan
    Route::get('/perakuan', Perakuan::class)->name('perakuan');

    // mesyuarat JTT
    Route::get('/mesyuarat-jtt', MesyuaratJtt::class)->name('mesyuarat-jtt');

    // prestasi
    Route::get('/prestasi/bulanan', Bulanan::class)->name('prestasi.bulanan');
    Route::get('/prestasi/kumulatif', Kumulatif::class)->name('prestasi.kumulatif');
});
