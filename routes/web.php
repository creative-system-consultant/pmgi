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
use App\Livewire\LoadingPerakuan;
use App\Livewire\LoadingPmgi;
use App\Livewire\Module\MaklumatWargaKerja;
use App\Livewire\Module\MesyuaratJtt;
use App\Livewire\Module\PegawaiDinilai;
use App\Livewire\Module\PegawaiMenilai;
use App\Livewire\Module\PegawaiPemudahCara;
use App\Livewire\Module\Perakuan;
use App\Livewire\Module\Prestasi\Bulanan;
use App\Livewire\Module\Prestasi\Kumulatif;
use App\Livewire\Module\RekodPmgi;
use App\Livewire\Module\Lantikan\Evaluator\Index as EvaluatorIndex;
use App\Livewire\Module\Lantikan\StateCommittee\Index as StateCommitteeIndex;
use App\Livewire\Module\Tetapan\OfficerInfo\Index as OfficerInfoIndex;
use App\Livewire\Module\Tetapan\UserAccessLevel\Index as UserAccessLevelIndex;
use App\Livewire\WaitingLobby;
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

Route::middleware(['auth', 'restrict.session'])->group(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('/dashboard-jtt', HomeJtt::class)->name('dashboard-jtt');

    Route::post('logout', LogoutController::class)
        ->name('logout');

    // maklumat warga kerja
    Route::get('/maklumat-warga-kerja', MaklumatWargaKerja::class)->name('maklumat-warga-kerja')->middleware('check.access:maklumat-warga-kerja');

    // mesyuarat JTT
    Route::get('/mesyuarat-jtt', MesyuaratJtt::class)->name('mesyuarat-jtt');

    // rekod PMGi (individu)
    Route::get('/rekod-pmgi', RekodPmgi::class)->name('rekod-pmgi')->middleware('check.access:rekod-pmgi');

    // prestasi
    Route::get('/prestasi/bulanan', Bulanan::class)->name('prestasi.bulanan')->middleware('check.access:prestasi-bulanan');
    Route::get('/prestasi/kumulatif', Kumulatif::class)->name('prestasi.kumulatif')->middleware('check.access:prestasi-kumulatif');

    // lantikan
    Route::get('/lantikan/urusetia-negeri', StateCommitteeIndex::class)->name('lantikan.urusetia-negeri')->middleware('check.access:lantikan-urusetia-negeri');
    Route::get('/lantikan/penilai', EvaluatorIndex::class)->name('lantikan.penilai')->middleware('check.access:lantikan-pym-mc');

    // tetapan
    Route::get('/tetapan/user-access', UserAccessLevelIndex::class)->name('tetapan.user-access')->middleware('check.access:tetapan-akses-pengguna');
    Route::get('/tetapan/info-pegawai', OfficerInfoIndex::class)->name('tetapan.info-pegawai')->middleware('check.access:tetapan-info-pyd-pym-pmc');
});

Route::middleware(['auth', 'ensure.session'])->group(function () {
    // PYD
    Route::get('/pegawai-dinilai', PegawaiDinilai::class)->name('pegawai-dinilai');

    // PYM
    Route::get('/pegawai-menilai', PegawaiMenilai::class)->name('pegawai-menilai');

    // PMC
    Route::get('/pegawai-pemudah-cara', PegawaiPemudahCara::class)->name('pegawai-pemudah-cara');

    //loading pmgi
    Route::get('/loading-pmgi', LoadingPmgi::class)->name('loading-pmgi');

    // perakuan
    Route::get('/perakuan', Perakuan::class)->name('perakuan');

    //loading perakuan
    Route::get('/loading-perakuan', LoadingPerakuan::class)->name('loading-perakuan');
});
