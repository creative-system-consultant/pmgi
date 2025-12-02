<?php

use App\Livewire\Home;
use App\Livewire\HomeJtt;
use App\Livewire\Auth\Login;
use App\Livewire\LoadingPmgi;
use App\Livewire\Auth\Register;
use App\Livewire\JttAttendance;
use App\Livewire\LoadingPerakuan;
use App\Livewire\Module\Perakuan;
use App\Livewire\Module\RekodPmgi;
use App\Livewire\Module\ListPydJtt;
use App\Livewire\Module\MesyuaratJtt;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Module\PegawaiDinilai;
use App\Livewire\Module\PegawaiMenilai;
use App\Livewire\Module\Prestasi\Bulanan;
use App\Http\Controllers\SearchController;
use App\Livewire\Module\MaklumatWargaKerja;
use App\Livewire\Module\PegawaiPemudahCara;
use App\Livewire\Module\Prestasi\Kumulatif;
use App\Livewire\Module\Tetapan\JttOfficer;
use App\Livewire\Module\Hr\Index as HrIndex;
use App\Livewire\Module\MasterListWargaKerja;
use App\Http\Middleware\RestrictDuringSession;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Module\Tetapan\PeratusanKriteria;
use App\Livewire\Module\Tetapan\MeetingRoom\MeetingRoom;
use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Livewire\Admin\AuditReport\PmgiAuditEvalPctg;
use App\Livewire\Admin\AuditReport\PmgiAuditJttRoles;
use App\Livewire\Admin\AuditReport\PmgiAuditMapBrancheshr2fms;
use App\Livewire\Admin\AuditReport\PmgiAuditMapStateshr2fms;
use App\Livewire\Admin\AuditReport\PmgiAuditMgrDesc;
use App\Livewire\Admin\AuditReport\PmgiAuditMntrSession;
use App\Livewire\Admin\AuditReport\PmgiAuditPmgiLevel;
use App\Livewire\Admin\AuditReport\PmgiAuditPmgiPeriod;
use App\Livewire\Admin\AuditReport\PmgiAuditPmgiResult;
use App\Livewire\Admin\ExceptionReport\PmgiExclBranch;
use App\Livewire\Admin\ExceptionReport\PmgiExcpMissingBranch;
use App\Livewire\Admin\ExceptionReport\PmgiExcpMissingMgr;
use App\Livewire\Admin\Maintenance\PmgiExclBranch as MaintenancePmgiExclBranch;
use App\Livewire\Admin\Maintenance\PmgiExclUserLogin;
use App\Livewire\Admin\Maintenance\pmgiRefMgrDesc;
use App\Livewire\Admin\Maintenance\PmgiMapBrancheshr2fms;
use App\Livewire\Admin\Maintenance\pmgiMapStateshr2fms;
use App\Livewire\Admin\Maintenance\pmgiRefEvalPctg;
use App\Livewire\Admin\Maintenance\PmgiRefJttRoles;
use App\Livewire\Admin\Maintenance\pmgiRefMntrSessionNotes;
use App\Livewire\Admin\Maintenance\pmgiRefpmgiLevel;
use App\Livewire\Admin\Maintenance\pmgiRefpmgiPeriod;
use App\Livewire\Admin\Maintenance\pmgiRefpmgiResult;
use App\Livewire\Admin\Report\JKPiCompletedOfficerByLevel;
use App\Livewire\Admin\Report\PmgiFMSBankOfficers;
use App\Livewire\Admin\Report\PmgiHrdOfficer;
use App\Livewire\Admin\Report\PmgiSysMsgLog;
use App\Livewire\Module\Lantikan\Evaluator\Index as EvaluatorIndex;
use App\Livewire\Module\Tetapan\OfficerInfo\Index as OfficerInfoIndex;
use App\Livewire\Module\Lantikan\StateCommittee\Index as StateCommitteeIndex;
use App\Livewire\Module\Tetapan\UserAccessLevel\Index as UserAccessLevelIndex;

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

// Maintenance routes (should be outside middleware group)
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
Route::get('/maintenance/status', [MaintenanceController::class, 'checkStatus'])->name('maintenance.status');

Route::middleware(['check.sysAvailable'])->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', function () {
            return redirect()->route('login');
        });

        Route::get('login', Login::class)->name('login');

        // Route::get('register', Register::class)
        //     ->name('register');

        // update JTT attendance
        Route::get('/confirm-attendance/{token}', [HomeJtt::class, 'confirmAttendance'])->name('confirm.attendance');
        // success landing page for jtt session
        Route::get('/jtt-attendance/{status}', JttAttendance::class)->name('jtt.attendance');
    });

    Route::get('password/reset', Email::class)
        ->name('password.request');

    Route::get('password/reset/{token}', Reset::class)
        ->name('password.reset');

    Route::middleware(['session.singleLogin'])->group(function () {
        // Route::middleware(['auth', 'check.sys.availability', 'check.role', 'restrict.session'])->group(function () {
        Route::middleware(['auth', 'check.role', 'restrict.session'])->group(function () {
            Route::get('/', Home::class)->name('home');

            Route::post('logout', LogoutController::class)
                ->name('logout');

            // maklumat warga kerja
            Route::get('/maklumat-warga-kerja', MaklumatWargaKerja::class)->name('maklumat-warga-kerja')->middleware('check.access:maklumat-warga-kerja');

            //  JTT.
            Route::get('/dashboard-jtt', HomeJtt::class)->name('dashboard-jtt');
            Route::get('/list-jtt', ListPydJtt::class)->name('list-pyd-jtt');
            Route::get('/mesyuarat-jtt', MesyuaratJtt::class)->name('mesyuarat-jtt');

            // MastErlist
            Route::get('/master-list-warga-kerja', MasterListWargaKerja::class)->name('master-list-warga-kerja')->middleware('check.access:masterlist-warga-kerja');

            // rekod PMGi (individu)
            Route::get('/rekod-pmgi', RekodPmgi::class)->name('rekod-pmgi')->middleware('check.access:rekod-pmgi');
            Route::get('/stream-pdf/{sessionId}', [RekodPmgi::class, 'streamRekodPmgi'])->name('stream.rekodPmgi')->withoutMiddleware([RestrictDuringSession::class]);
            Route::get('/stream/attachment', [RekodPmgi::class, 'streamAttachment'])->name('stream.attachment')->withoutMiddleware([RestrictDuringSession::class]);

            // prestasi
            Route::get('/prestasi/bulanan', Bulanan::class)->name('prestasi.bulanan')->middleware('check.access:prestasi-bulanan');
            Route::get('/prestasi/kumulatif', Kumulatif::class)->name('prestasi.kumulatif')->middleware('check.access:prestasi-kumulatif');

            // lantikan
            Route::get('/lantikan/urusetia-negeri', StateCommitteeIndex::class)->name('lantikan.urusetia-negeri')->middleware('check.access:lantikan-urusetia-negeri');
            Route::get('/lantikan/penilai', EvaluatorIndex::class)->name('lantikan.penilai')->middleware('check.access:lantikan-pym-mc');

            // tetapan
            Route::get('/tetapan/user-access', UserAccessLevelIndex::class)->name('tetapan.user-access')->middleware('check.access:tetapan-akses-pengguna');
            Route::get('/tetapan/info-pegawai', OfficerInfoIndex::class)->name('tetapan.info-pegawai')->middleware('check.access:tetapan-info-pyd-pym-pmc');
            Route::get('/tetapan/ahli-jtt', JttOfficer::class)->name('tetapan.ahli-jtt')->middleware('check.access:tetapan-ahli-jtt');
            Route::get('/tetapan/meeting-room', MeetingRoom::class)->name('tetapan.meeting-room')->middleware('check.access:tetapan-bilik-meeting');
            Route::get('/tetapan/peratusan-kriteria', PeratusanKriteria::class)->name('tetapan.peratusan-kriteria')->middleware('check.access:tetapan-peratusan-kriteria');

            // HR
            Route::get('/hr/{userid}', HrIndex::class)->name('hr.index');

            // search purpose
            Route::get('/staff-search', [SearchController::class, 'staffName'])->name('staff-name-search');
            Route::get('/staff-search-by-branch', [SearchController::class, 'staffNameByBranch'])->name('staff-name-search-by-branch');

            // Penyelenggaraan (Admin only)
            Route::prefix('admin-maintenance')->name('maintenance.admin.')->middleware('check.access:admin-penyelenggaraan')->group(function () {
                Route::get('/mgr-description', pmgiRefMgrDesc::class)->name('ref_mgr_desc');
                Route::get('/pmgi-result', pmgiRefpmgiResult::class)->name('ref_pmgi_result');
                Route::get('/monitor-session-notes', pmgiRefMntrSessionNotes::class)->name('monitor_session_notes');
                Route::get('/pmgi-level', pmgiRefpmgiLevel::class)->name('ref_pmgi_level');
                Route::get('/pmgi-period', pmgiRefpmgiPeriod::class)->name('ref_pmgi_period');
                Route::get('/map-states-hr2fms', pmgiMapStateshr2fms::class)->name('map_state_hr2fms');
                Route::get('/eval-percentage', pmgiRefEvalPctg::class)->name('ref_eval_pctg');
                Route::get('/map-branches-hr2fms', PmgiMapBrancheshr2fms::class)->name('map_branches_hr2fms');
                Route::get('/pmgi-excl-user-login', PmgiExclUserLogin::class)->name('excl_user_login');
                Route::get('/pmgi-excl-branch', MaintenancePmgiExclBranch::class)->name('maintenance_excl_branch');
                Route::get('/jtt-roles', PmgiRefJttRoles::class)->name('ref_jtt_roles');
            });

            // Laporan Khas (Admin Only)
            Route::prefix('admin-special-report')->name('exceptionReport.admin.')->middleware('check.access:admin-laporan-khas')->group(function () {
                // Laporan Pengecualian
                Route::get('/excl-branch', PmgiExclBranch::class)->name('excl_branch');
                Route::get('/excp-misssing-branch', PmgiExcpMissingBranch::class)->name('excp_missing_branch');
                Route::get('/excp-missing-mgr', PmgiExcpMissingMgr::class)->name('excp_missing_mgr');   
                
                // Laporan Audit
                Route::get('/audit-mgr-desc', PmgiAuditMgrDesc::class)->name('audit_mgr_desc');
                Route::get('/audit-pmgi-result', PmgiAuditPmgiResult::class)->name('audit_pmgi_result');
                Route::get('/audit-monitor-session-notes', PmgiAuditMntrSession::class)->name('audit_monitor_session_notes');
                Route::get('/audit-pmgi-level', PmgiAuditPmgiLevel::class)->name('audit_pmgi_level');
                Route::get('/audit-pmgi-period', PmgiAuditPmgiPeriod::class)->name('audit_pmgi_period');
                Route::get('/audit-map-states-hr2fms', PmgiAuditMapStateshr2fms::class)->name('audit_map_state_hr2fms');
                Route::get('/audit-eval-percentage', PmgiAuditEvalPctg::class)->name('audit_eval_pctg');
                Route::get('/audit-map-branches-hr2fms', PmgiAuditMapBrancheshr2fms::class)->name('audit_map_branches_hr2fms');
                Route::get('/audit-jtt-roles', PmgiAuditJttRoles::class)->name('audit_jtt_roles');

                // Laporan Sistem
                Route::get('/sys-msg-log', PmgiSysMsgLog::class)->name('sys_msg_log');
            });

            // Laporan (Admin Only)
            Route::prefix('admin-report')->name('report.admin.')->middleware('check.access:admin-laporan')->group(function () {                  
                Route::get('/fms-bank-officer', PmgiFMSBankOfficers::class)->name('fms_bank_officer');         
                Route::get('/fms-hrd-officer', PmgiHrdOfficer::class)->name('fms_hrd_officer');         
                Route::get('/senarai-pengawai-JKPi', JKPiCompletedOfficerByLevel::class)->name('senarai_pengawai_JKPi');         
            });        
        });

        Route::middleware(['auth', 'check.role', 'ensure.session'])->group(function () {
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
    });
});
