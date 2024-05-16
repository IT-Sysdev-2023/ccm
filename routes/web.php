<?php

use App\Http\Controllers\AllTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchInputController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportUpdateController;
use App\Http\Controllers\DsBounceTaggingController;
use App\Http\Controllers\DatedPdcChecksController;
use App\Http\Controllers\CheckReceivingController;
use App\Http\Controllers\AccountingReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


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
//     return Inertia::render('Auth/Login');
// });

Route::middleware('guest')->get('/', [AuthenticatedSessionController::class, 'create'])->name('index');

Route::fallback(function () {
    $previousUrl = url()->previous();
    return Inertia::render('NotFoundPage/NotFound', [
        'previousUrl' =>  $previousUrl
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'adminDashboardComponent'])->name('admin.dashboard');
    Route::get('accounting/dashboard', [DashboardController::class, 'accountingDashboard'])->name('accounting.dashboard');
    Route::get('treasury/dashboard', [DashboardController::class, 'treasuryDashboardComponent'])->name('treasury.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('settings', [UserController::class, 'settings'])->name('settings');
    Route::put('update/username', [UserController::class, 'updateUsername'])->name('username.edit');
    Route::put('update/user/', [UserController::class, 'updateUser'])->name('users.update');
    Route::post('create/user', [UserController::class, 'createUser'])->name('users.store');
    Route::get('user/details/{id}', [UserController::class, 'userDetails'])->name('users.details');
    Route::get('auto/complete/users/search', [UserController::class, 'searchUsers'])->name('users.search');
    Route::get('auto/complete/company/search', [UserController::class, 'searchCompany'])->name('company.search');
    Route::get('auto/complete/bunit/search', [UserController::class, 'searchBunit'])->name('bunit.search');
    Route::get('auto/completedepartment/search', [UserController::class, 'searchDepartment'])->name('department.search');
    Route::get('export/excel/users', [UserController::class, 'exportExcel'])->name('users.excel');
    Route::post('resign/reactive/', [UserController::class, 'resignReactive'])->name('users.resrec');
    Route::get('search/employee', [UserController::class, 'searchAnEmployeeName'])->name('searchAnEmployeeName');


    Route::get('dated/pdc/checks/reports', [ReportController::class, 'datedpdcchecksreports'])->name('reports.dpdc');
    Route::get('deposited/checks/reports', [ReportController::class, 'depositedCheckReports'])->name('deposited.checks');
    Route::get('bounce/checks/reports', [ReportController::class, 'bounceCheckReports'])->name('bounce.checks.report');
    Route::get('dated/pdc/checks/report', [ReportController::class, 'get_dated_pdc_checks_rep'])->name('get.dpdc');
    Route::get('generate/report/excel', [ReportController::class, 'generate_reps_to_excel'])->name('excel.dpdc');
    Route::get('start/generating/depositedchecks', [ReportController::class, 'startGeneratingDepositedChecks'])->name('startgenerate.depchecks');
    Route::get('start/generating/bouncechecks', [ReportController::class, 'startGeneratingBounceCheckReport'])->name('startgenerate.bounceChecks');
    Route::get('redeem/check/reports', [ReportController::class, 'redeemCheckReportsAdmin'])->name('redeem.reports.admin');
    Route::get('start/generating/redeem/report', [ReportController::class, 'startGeneratingRedeemReports'])->name('start.generate.redeem.reports');
    Route::get('alta-cita/report', [ReportController::class, 'checksInAltaReports'])->name('alta.reports');
    Route::get('start/generate/report', [ReportController::class, 'startGenerateAltaReports'])->name('start.generate.alta');




    Route::controller(ImportUpdateController::class)->group(function () {
        Route::get('indeximportupdates', 'indeximportupdates')->name('indeximportupdates');
        Route::get('instImport', 'instImportfunction')->name('instImport');
        Route::get('update/atp/database', [ImportUpdateController::class, 'updateAtpDatabase'])->name('updatingAtp.database');
    });

    Route::get('bounce/tagging/index', [DsBounceTaggingController::class, 'indexBounceTagging'])->name('bounce.tagging');
    Route::get('dstagging', [DsBounceTaggingController::class, 'indexDsTagging'])->name('ds_tagging');
    Route::get('bounce/tagging', [DsBounceTaggingController::class, 'get_bounce_tagging'])->name('get_bounce_tagging');
    Route::post('tag_check_bounce', [DsBounceTaggingController::class, 'tagCheckBounce'])->name('tag_check_bounce');
    Route::post('submit-ds-tagging', [DsBounceTaggingController::class, 'submiCheckDs'])->name('submit.ds.tagging');
    Route::put('update-switch', [DsBounceTaggingController::class, 'updateSwitch'])->name('update.switch');

    Route::get('pdc/checks', [DatedPdcChecksController::class, 'pdc_index'])->name('pdc.checks');
    Route::get('dated/checks', [DatedPdcChecksController::class, 'dated_index'])->name('dated.checks');
    Route::post('pdc/cash/replacement', [DatedPdcChecksController::class, 'pdc_cash_replacement'])->name('pdc_cash.replacement');
    Route::post('pdc/check/replacement', [DatedPdcChecksController::class, 'pdc_check_replacement'])->name('pdc_check.replacement');
    Route::post('pdc/cash/check/replacement', [DatedPdcChecksController::class, 'pdc_check_cash_replacement'])->name('pdc_cash_check.replacement');
    Route::post('pdc/cash/partial/replacement', [DatedPdcChecksController::class, 'pdc_partial_replacement_cash'])->name('pdc_cash_partial.replacement');
    Route::post('pdc/check/partial/replacement', [DatedPdcChecksController::class, 'pdc_partial_replacement_check'])->name('pdc_check_partial.replacement');



    Route::get('search/checkfrom/', [SearchInputController::class, 'searchCheckFrom'])->name('search.checkfrom');
    Route::get('search/bank/name', [SearchInputController::class, 'searchBankName'])->name('search.bankName');
    Route::get('search/customer/name', [SearchInputController::class, 'searchCustomerName'])->name('search.customerName');
    Route::get('search/employee/name', [SearchInputController::class, 'searchEmployee'])->name('search.employeeName');
    Route::get('search/bunit/name', [SearchInputController::class, 'searchBunit'])->name('search.bunit');
    Route::get('search/company/name', [SearchInputController::class, 'searchCompany'])->name('search.company');



    Route::get('check/for/clearing', [CheckReceivingController::class, 'getCheckForClearing'])->name('check_for.clearing');
    Route::get('search/dated', [CheckReceivingController::class, 'searchDatedChecks'])->name('search_dated');
    Route::get('check/uncheck', [CheckReceivingController::class, 'checkAndUncheck'])->name('checkUncheck.checks');
    Route::post('save/dated/leasing/pdc/checks', [CheckReceivingController::class, 'savedDatedLeasingpPdcChecks'])->name('datedleaspdc.checks');
    Route::get('pdccheck/clearing', [CheckReceivingController::class, 'pdcChecksCLearing'])->name('pdc_clearing.checks');
    Route::get('leasing/checks', [CheckReceivingController::class, 'getLeasingChecks'])->name('leasing.checks');


    Route::get('check/manual/entry', [AllTransactionController::class, 'getCheckManualEntry'])->name('manual_entry.checks');
    Route::post('check/manual/entry/store', [AllTransactionController::class, 'checkManualEntryStore'])->name('manual_entry_store.checks');
    Route::get('merge/checks', [AllTransactionController::class, 'getMergeChecks'])->name('mergechecks.checks');
    Route::post('merge/checks/store', [AllTransactionController::class, 'getMergeCheckStore'])->name('mergecheckstore.checks');
    Route::get('bounce/checks', [AllTransactionController::class, 'getBounceChecks'])->name('bounce.checks');
    Route::get('replaced/checks', [AllTransactionController::class, 'getCheckReplace'])->name('replace.checks');
    Route::get('partial/payments/checks', [AllTransactionController::class, 'getPartialPayment'])->name('partial_payments.checks');
    Route::get('dated/checkpdc/reports', [AllTransactionController::class, 'getDatedPdcReports'])->name('dcpdc.checks');
    Route::get('generate/report', [AllTransactionController::class, 'generate_report'])->name('generate_report.checks');
    Route::get('duepdc/reports', [AllTransactionController::class, 'getDuepdcReports'])->name('duePdcReports.checks');
    Route::post('generate/report/duepdc', [AllTransactionController::class, 'generateExcelDuePdcReports'])->name('generate_duepdcrep.checks');

    Route::get('replacement/details/checks', [AllTransactionController::class, 'replacementDetails'])->name('replacment.details');
    Route::get('replacement/partial/payment', [AllTransactionController::class, 'replacedPartialPaymentTable'])->name('replacmentpartialTable.details');
    Route::get('replacement/partial/payment/not/null', [AllTransactionController::class, 'partialPaymentTableNotNull'])->name('partialpaynotnull.partials');
    Route::get('replacement/partial/payment/null', [AllTransactionController::class, 'partialPaymentTableNull'])->name('partialpaynull.partials');
    Route::post('submit/partial/payment', [AllTransactionController::class, 'submitPartialPayment'])->name('submit.partials');
    Route::post('submit/partial/check/payment', [AllTransactionController::class, 'submitPartialPaymentCheck'])->name('submitcheck.partials');
    Route::get('bounce/check/type', [AllTransactionController::class, 'getBounceCheckType'])->name('bounceCheckType.checks');

    Route::post('bounce/cash/replacement', [AllTransactionController::class, 'bouncedCashReplacement'])->name('bounceCash.replace');
    Route::post('bounce/check/replacement', [AllTransactionController::class, 'bouncedCheckReplacement'])->name('bounceCheck.replace');
    Route::post('bounce/check/cash/replacement', [AllTransactionController::class, 'bouncedCheckCashReplacement'])->name('bounceCheckCash.replace');
    Route::post('bounce/redeposit/replacement', [AllTransactionController::class, 'bounceCheckReDeposit'])->name('bounceReDeposit.replace');
    Route::post('bounce/partial/cash', [AllTransactionController::class, 'bouncePartialPaymentCash'])->name('bouncePartialCash.replace');
    Route::post('bounce/partial/check', [AllTransactionController::class, 'bouncePartialReplaymentCheck'])->name('bouncePartialCheck.replace');

    Route::get('reports/accounting', [AccountingReportController::class, 'reportIndex'])->name('reports.accounting');
    Route::get('reports/datedpdcchecks', [AccountingReportController::class, 'innerReportDatedPdcCheques'])->name('datedpcchecks.accounting');
    Route::get('start/generatng/report/accounting', [AccountingReportController::class, 'startGeneratingAccountingReports'])->name('start.generate.rep.accouting');
    Route::get('reports/deposited/checks', [AccountingReportController::class, 'innerDepositedCheckReports'])->name('deposited.reports.accounting');
    Route::get('start/generatng/deposited/accounting', [AccountingReportController::class, 'startGeneratingDepositedAccountingReports'])->name('start.generate.rep,deposited.accouting');
    Route::get('bounce/checks/report/accounting', [AccountingReportController::class, 'bounceCheckReportAccounting'])->name('bounce.checks.accounting');
    Route::get('start/generating/bounce/report/accounting', [AccountingReportController::class, 'startGeneratingReportsChequesAccounting'])->name('start.bounce.accounting');
    Route::get('redeem/check/accounting/report', [AccountingReportController::class, 'redeemPdcCheckAccountingReports'])->name('redeem.reports.accounting');
    Route::get('start/generating/redpdc/report', [AccountingReportController::class, 'startGeneratingRedeemPdcAccounting'])->name('start.generating.redpdc.accounting');

    Route::get('/download/excel/{filename}', function ($filename) {
        $filePath = storage_path('app/' . $filename);
        return response()->download($filePath);
    })->name('download.excel');

    Route::get('start/importing/checks', [ImportUpdateController::class, 'startImportChecks'])->name('start.importing.checks');
});

require __DIR__ . '/auth.php';
