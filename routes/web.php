<?php

use App\Http\Controllers\AllTransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportUpdateController;
use App\Http\Controllers\DsBounceTaggingController;
use App\Http\Controllers\DatedPdcChecksController;
use App\Http\Controllers\CheckReceivingController;
use Illuminate\Support\Facades\Storage;

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

Route::get('/', function () {
    // dd(User::all());
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return Inertia::render('AdminDashboard');
    })->name('admin_dashboard');
    Route::get('/accounting/dashboard', function () {
        return Inertia::render('AccountingDashboard');
    })->name('accounting_dashboard');
    Route::get('/treasury/dashboard', function () {
        return Inertia::render('TreasuryDashboard');
    })->name('treasury_dashboard');
});

Route::middleware('auth')->group(function () {

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });


    //User Controller
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::post('/update_user/{id}', 'updateUser')->name('users.update');
        Route::post('/create/user', 'createUser')->name('users.store');
        Route::get('/user/details/{id}', 'userDetails')->name('users.details');
        Route::get('/autoc_users/search', 'searchUsers')->name('users.search');
        Route::get('/autoc_company/search', 'searchCompany')->name('company.search');
        Route::get('/autoc_bunit/search', 'searchBunit')->name('bunit.search');
        Route::get('/autoc_department/search', 'searchDepartment')->name('department.search');
        Route::get('/export-excel/users', 'exportExcel')->name('users.excel');
        Route::post('/resign-reactive/{id}', 'resignReactive')->name('users.resrec');
        Route::get('/search_an_employee', 'searchAnEmployeeName')->name('searchAnEmployeeName');
    });


    Route::controller(ReportController::class)->group(function () {
        Route::get('datedpdcchecks-reports', 'datedpdcchecksreports')->name('reports.dpdc');
        Route::get('get_dated_pdc_checks_rep', 'get_dated_pdc_checks_rep')->name('get.dpdc');
        Route::get('generate_reps_to_excel', 'generate_reps_to_excel')->name('excel.dpdc');
    });

    Route::controller(ImportUpdateController::class)->group(function () {
        Route::get('/indeximportupdates', 'indeximportupdates')->name('indeximportupdates');
        Route::get('instImport', 'instImportfunction')->name('instImport');
    });

    Route::controller(DsBounceTaggingController::class)->group(function () {
        Route::get('/bounce_tagging', 'indexBounceTagging')->name('bounce_tagging');
        Route::get('/ds_tagging', 'indexDsTagging')->name('ds_tagging');
        Route::get('/get_bounce_tagging', 'get_bounce_tagging')->name('get_bounce_tagging');
        Route::post('/tag_check_bounce', 'check_for.clearingtag_check_bounce')->name('tag_check_bounce');
        Route::post('/submit-ds-tagging', 'submiCheckDs')->name('submit.ds.tagging');
        Route::put('update-switch', 'updateSwitch')->name('update.switch');
    });

    Route::controller(DatedPdcChecksController::class)->group(function () {
        Route::get('pdc_checks', 'pdc_index')->name('pdc.checks');
        Route::get('dated_checks', 'dated_index')->name('dated.checks');
    });


    Route::controller(CheckReceivingController::class)->group(function () {
        Route::get('check_for_clearing', 'getCheckForClearing')->name('check_for.clearing');
        Route::get('search_dated', 'searchDatedChecks')->name('search_dated');
        Route::get('check-uncheck', 'checkAndUncheck')->name('checkUncheck.checks');
        Route::post('save_dated_leasing_pdc_checks', 'savedDatedLeasingpPdcChecks')->name('datedleaspdc.checks');
        Route::get('pdc_check_clearing', 'pdcChecksCLearing')->name('pdc_clearing.checks');
        Route::get('leasing_checks', 'getLeasingChecks')->name('leasing.checks');
    });



    Route::get('check/manual/entry', [AllTransactionController::class, 'getCheckManualEntry'])->name('manual_entry.checks');
    Route::post('check/manual/entry/store', [AllTransactionController::class, 'checkManualEntryStore'])->name('manual_entry_store.checks');
    Route::get('merge/checks', [AllTransactionController::class, 'getMergeChecks'])->name('mergechecks.checks');
    Route::post('merge/checks/store', [AllTransactionController::class, 'getMergeCheckStore'])->name('mergecheckstore.checks');
    Route::get('bounce/checks', [AllTransactionController::class, 'getBounceChecks'])->name('bounce.checks');
    Route::get('replace_checks', [AllTransactionController::class, 'getCheckReplace'])->name('replace.checks');
    Route::get('partial_payments_checks', [AllTransactionController::class, 'getPartialPayment'])->name('partial_payments.checks');
    Route::get('dated_check_pdc_reports', [AllTransactionController::class, 'getDatedPdcReports'])->name('dcpdc.checks');
    Route::get('generate_report', [AllTransactionController::class, 'generate_report'])->name('generate_report.checks');
    Route::get('get_due_pdc_reports', [AllTransactionController::class, 'getDuepdcReports'])->name('duePdcReports.checks');
    Route::get('generate_report_due_pdc', [AllTransactionController::class, 'generateExcelDuePdcReports'])->name('generate_duepdcrep.checks');

    Route::post('bounce/cash/replacement', [AllTransactionController::class, 'bouncedCashReplacement'])->name('bounceCash.replace');
    Route::post('bounce/check/replacement', [AllTransactionController::class, 'bouncedCheckReplacement'])->name('bounceCheck.replace');
    Route::post('bounce/check/cash/replacement', [AllTransactionController::class, 'bouncedCheckCashReplacement'])->name('bounceCheckCash.replace');
    Route::post('bounce/redeposit/replacement', [AllTransactionController::class, 'bounceCheckReDeposit'])->name('bounceReDeposit.replace');

    Route::get('/download/excel/{filename}', function ($filename) {
        $filePath = storage_path('app/' . $filename);
        return response()->download($filePath);
    })->name('download.excel');

    Route::get('start_importing_checks', [ImportUpdateController::class, 'startImportChecks'])->name('start.importing.checks');
});

require __DIR__ . '/auth.php';
