<?php

use App\Http\Controllers\AllTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchInputController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/update_user/{id}', [UserController::class, 'updateUser'])->name('users.update');
    Route::post('/create/user', [UserController::class, 'createUser'])->name('users.store');
    Route::get('/user/details/{id}', [UserController::class, 'userDetails'])->name('users.details');
    Route::get('/autoc_users/search', [UserController::class, 'searchUsers'])->name('users.search');
    Route::get('/autoc_company/search', [UserController::class, 'searchCompany'])->name('company.search');
    Route::get('/autoc_bunit/search', [UserController::class, 'searchBunit'])->name('bunit.search');
    Route::get('/autoc_department/search', [UserController::class, 'searchDepartment'])->name('department.search');
    Route::get('/export-excel/users', [UserController::class, 'exportExcel'])->name('users.excel');
    Route::post('/resign-reactive/{id}', [UserController::class, 'resignReactive'])->name('users.resrec');
    Route::get('/search_an_employee', [UserController::class, 'searchAnEmployeeName'])->name('searchAnEmployeeName');


    Route::get('datedpdcchecks-reports', [ReportController::class, 'datedpdcchecksreports'])->name('reports.dpdc');
    Route::get('get_dated_pdc_checks_rep', [ReportController::class, 'get_dated_pdc_checks_rep'])->name('get.dpdc');
    Route::get('generate_reps_to_excel', [ReportController::class, 'generate_reps_to_excel'])->name('excel.dpdc');




    Route::get('/indeximportupdates', [ImportUpdateController::class, 'indeximportupdates'])->name('indeximportupdates');
    Route::get('instImport', [ImportUpdateController::class, 'instImportfunction'])->name('instImport');

    Route::get('/bounce_tagging', [DsBounceTaggingController::class, 'indexBounceTagging'])->name('bounce_tagging');
    Route::get('/ds_tagging', [DsBounceTaggingController::class, 'indexDsTagging'])->name('ds_tagging');
    Route::get('/get_bounce_tagging', [DsBounceTaggingController::class, 'get_bounce_tagging'])->name('get_bounce_tagging');
    Route::post('/tag_check_bounce', [DsBounceTaggingController::class, 'check_for.clearingtag_check_bounce'])->name('tag_check_bounce');
    Route::post('/submit-ds-tagging', [DsBounceTaggingController::class, 'submiCheckDs'])->name('submit.ds.tagging');
    Route::put('update-switch', [DsBounceTaggingController::class, 'updateSwitch'])->name('update.switch');

    Route::get('pdc_checks', [DatedPdcChecksController::class, 'pdc_index'])->name('pdc.checks');
    Route::get('dated_checks', [DatedPdcChecksController::class, 'dated_index'])->name('dated.checks');
    Route::post('pdc/cash/replacement', [DatedPdcChecksController::class, 'pdc_cash_replacement'])->name('pdc_cash.replacement');
    Route::post('pdc/check/replacement', [DatedPdcChecksController::class, 'pdc_check_replacement'])->name('pdc_check.replacement');
    Route::post('pdc/cash/check/replacement', [DatedPdcChecksController::class, 'pdc_check_cash_replacement'])->name('pdc_cash_check.replacement');
    Route::post('pdc/cash/partial/replacement', [DatedPdcChecksController::class, 'pdc_partial_replacement_cash'])->name('pdc_cash_partial.replacement');
    Route::post('pdc/check/partial/replacement', [DatedPdcChecksController::class, 'pdc_partial_replacement_check'])->name('pdc_check_partial.replacement');



    Route::get('search/checkfrom/', [SearchInputController::class, 'searchCheckFrom'])->name('search.checkfrom');
    Route::get('search/bank/name', [SearchInputController::class, 'searchBankName'])->name('search.bankName');
    Route::get('search/customer/name', [SearchInputController::class, 'searchCustomerName'])->name('search.customerName');
    Route::get('search/employee/name', [SearchInputController::class, 'searchEmployee'])->name('search.employeeName');



    Route::get('check_for_clearing', [CheckReceivingController::class, 'getCheckForClearing'])->name('check_for.clearing');
    Route::get('search_dated', [CheckReceivingController::class, 'searchDatedChecks'])->name('search_dated');
    Route::get('check-uncheck', [CheckReceivingController::class, 'checkAndUncheck'])->name('checkUncheck.checks');
    Route::post('save_dated_leasing_pdc_checks', [CheckReceivingController::class, 'savedDatedLeasingpPdcChecks'])->name('datedleaspdc.checks');
    Route::get('pdc_check_clearing', [CheckReceivingController::class, 'pdcChecksCLearing'])->name('pdc_clearing.checks');
    Route::get('leasing_checks', [CheckReceivingController::class, 'getLeasingChecks'])->name('leasing.checks');


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
    Route::post('bounce/partial/cash', [AllTransactionController::class, 'bouncePartialPaymentCash'])->name('bouncePartialCash.replace');
    Route::post('bounce/partial/check', [AllTransactionController::class, 'bouncePartialReplaymentCheck'])->name('bouncePartialCheck.replace');

    Route::get('/download/excel/{filename}', function ($filename) {
        $filePath = storage_path('app/' . $filename);
        return response()->download($filePath);
    })->name('download.excel');

    Route::get('start_importing_checks', [ImportUpdateController::class, 'startImportChecks'])->name('start.importing.checks');
});

require __DIR__ . '/auth.php';
