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
use App\Http\Controllers\AddCredentialController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DetailsController;

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
Route::get('/employee/name', [SearchInputController::class, 'searchEmployee'])->name('search.employeeName');
Route::fallback(function () {
    // $previousUrl = url()->previous();
    return Inertia::render('NotFoundPage/NotFound', [
        // 'previousUrl' =>  $previousUrl
    ]);

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'adminDashboardComponent'])->name('admin.dashboard');
    Route::get('accounting/dashboard', [DashboardController::class, 'accountingDashboard'])->name('accounting.dashboard');
    Route::get('treasury/dashboard', [DashboardController::class, 'treasuryDashboardComponent'])->name('treasury.dashboard');
    Route::get('about', [DashboardController::class, 'aboutUs'])->name('about.us');
});

Route::middleware('auth')->group(function () {

    //Users Side
    Route::prefix('users')->group(function () {
        Route::get('index', [UserController::class, 'index'])->name('users.index');
        Route::get('settings', [UserController::class, 'settings'])->name('mySettings.accouting');
        Route::put('update/username', [UserController::class, 'updateUsername'])->name('username.edit');
        Route::put('update/information/', [UserController::class, 'updateUser'])->name('users.update');
        Route::post('create', [UserController::class, 'createUser'])->name('users.store');
        Route::get('details/{id}', [UserController::class, 'userDetails'])->name('users.details');
        Route::get('name/search', [UserController::class, 'searchUsers'])->name('users.search');
        Route::get('company/search', [UserController::class, 'searchCompany'])->name('company.search');
        Route::get('bunit/search', [UserController::class, 'searchBunit'])->name('bunit.search');
        Route::get('department/search', [UserController::class, 'searchDepartment'])->name('department.search');
        Route::get('export/excel', [UserController::class, 'exportExcel'])->name('users.excel');
        Route::post('resign/reactive', [UserController::class, 'resignReactive'])->name('users.resrec');
        Route::get('search/employee', [UserController::class, 'searchAnEmployeeName'])->name('searchAnEmployeeName');
        Route::get('search/applicant', [UserController::class, 'getUsersApplicantEmployee3'])->name('searchApplicant');
    });

    //Reports
    Route::prefix('reports')->group(function () {
        Route::get('dated/pdc/checks', [ReportController::class, 'datedpdcchecksreports'])->name('reports.dpdc');
        Route::get('deposited/checks', [ReportController::class, 'depositedCheckReports'])->name('deposited.checks');
        Route::get('bounce/checks', [ReportController::class, 'bounceCheckReports'])->name('bounce.checks.report');
        Route::get('dated/pdc/generate', [ReportController::class, 'generateExcelDatedChecksPdc'])->name('generate.datedpdc.excel');
        Route::get('generate/report', [ReportController::class, 'generate_reps_to_excel'])->name('excel.dpdc');
        Route::get('start/generating', [ReportController::class, 'startGeneratingDepositedChecks'])->name('startgenerate.depchecks');
        Route::get('start/generating/bounce', [ReportController::class, 'startGeneratingBounceCheckReport'])->name('bounce.generate.reports');
        Route::get('redeem/check', [ReportController::class, 'redeemCheckReportsAdmin'])->name('redeem.reports.admin');
        Route::get('start/generating/redeem', [ReportController::class, 'startGeneratingRedeemReports'])->name('start.generate.redeem.reports');
        Route::get('alta-cita', [ReportController::class, 'checksInAltaReports'])->name('alta.reports');
        Route::get('alta-cita/details', [ReportController::class, 'altaCitaDetails'])->name('alta.details');
        Route::get('start/generate', [ReportController::class, 'startGenerateAltaReports'])->name('start.generate.alta');
    });

    //Update Imports Management
    Route::prefix('management')->group(function () {
        Route::get('start/importing/checks', [ImportUpdateController::class, 'startImportChecks'])->name('start.importing.checks');
        Route::controller(ImportUpdateController::class)->group(function () {
            Route::get('indeximportupdates', 'indeximportupdates')->name('indeximportupdates');
            Route::get('instImport', 'instImportfunction')->name('instImport');
        });
        Route::get('update/atp/database', [ImportUpdateController::class, 'updateAtpDatabase'])->name('updatingAtp.database');
    });

    //Bounce and Taggings
    Route::prefix('bouncetaggings')->group(function () {
        Route::get('bounce/tagging/index', [DsBounceTaggingController::class, 'indexBounceTagging'])->name('bounce.tagging');
        Route::get('dstagging', [DsBounceTaggingController::class, 'indexDsTagging'])->name('ds_tagging');
        Route::get('search/dsTagging', [DsBounceTaggingController::class, 'searchDsTagging'])->name('search.dstagging');
        Route::get('dstagging-axios', [DsBounceTaggingController::class, 'getDsTaggings'])->name('axios.get.tagging');
        Route::get('bounce/tagging', [DsBounceTaggingController::class, 'get_bounce_tagging'])->name('get_bounce_tagging');
        Route::post('tag_check_bounce', [DsBounceTaggingController::class, 'tagCheckBounce'])->name('tag_check_bounce');
        Route::post('submit-ds-tagging', [DsBounceTaggingController::class, 'submiCheckDs'])->name('submit.ds.tagging');
        Route::put('update-switch', [DsBounceTaggingController::class, 'updateSwitch'])->name('update.switch');
    });
    // Checks Processings
    Route::prefix('checkprocessing')->group(function () {
        Route::get('pdc/checks', [DatedPdcChecksController::class, 'pdc_index'])->name('pdc.checks');
        Route::get('dated/checks', [DatedPdcChecksController::class, 'dated_index'])->name('dated.checks');
        Route::post('pdc/cash/replacement', [DatedPdcChecksController::class, 'pdc_cash_replacement'])->name('pdc_cash.replacement');
        Route::post('pdc/check/replacement', [DatedPdcChecksController::class, 'pdc_check_replacement'])->name('pdc_check.replacement');
        Route::post('pdc/cash/check/replacement', [DatedPdcChecksController::class, 'pdc_check_cash_replacement'])->name('pdc_cash_check.replacement');
        Route::post('pdc/cash/partial/replacement', [DatedPdcChecksController::class, 'pdc_partial_replacement_cash'])->name('pdc_cash_partial.replacement');
        Route::post('pdc/check/partial/replacement', [DatedPdcChecksController::class, 'pdc_partial_replacement_check'])->name('pdc_check_partial.replacement');
    });
    //Search Engine
    Route::prefix('search')->group(function () {
        Route::get('checkfrom/', [SearchInputController::class, 'searchCheckFrom'])->name('search.checkfrom');
        Route::get('bank/name', [SearchInputController::class, 'searchBankName'])->name('search.bankName');
        Route::get('customer/name', [SearchInputController::class, 'searchCustomerName'])->name('search.customerName');
        Route::get('employee/name', [SearchInputController::class, 'searchEmployee'])->name('search.employeeName');
        Route::get('bunit/name', [SearchInputController::class, 'searchBunit'])->name('search.bunit');
        Route::get('company/name', [SearchInputController::class, 'searchCompany'])->name('search.company');
    });
    //Adjustments
    Route::prefix('adjustments')->group(function () {
        Route::get('check/adjustments', [AdjustmentController::class, 'editChecksAdjustment'])->name('checks.adjustment');
        Route::put('update/adjustments', [AdjustmentController::class, 'updateCheckAdjustments'])->name('update.adjustments');
        Route::get('deposit/adjustments', [AdjustmentController::class, 'depositAdjustments'])->name('deposit.adjustment');
        Route::put('dsNumber/adjustments', [AdjustmentController::class, 'updateDsNumber'])->name('update.dsNo.adjustments');
        Route::get('bounce/adjustments', [AdjustmentController::class, 'bounceChecksAdjustments'])->name('bounce.checks.adjustments');
        Route::put('rebounce/adjustments', [AdjustmentController::class, 'reBounceCheckAdjustments'])->name('rebounce.adjustments');
        Route::get('replace/adjustments', [AdjustmentController::class, 'replacementAdjustments'])->name('replace.checks.adjustments');
        Route::put('update/re-deposit/checks', [AdjustmentController::class, 'updateRedepositChecks'])->name('update.rediposit.checks');
        Route::put('cancel/re-deposit/checks', [AdjustmentController::class, 'cancelRedepositChecks'])->name('cancel.replacement.checks');
        Route::put('update/cash/checks', [AdjustmentController::class, 'updateCashAdjustments'])->name('update.cash.checks');
        Route::put('update/cash/checks', [AdjustmentController::class, 'updateCashAdjustments'])->name('update.cash.checks');
        Route::put('update/cash-check/checks', [AdjustmentController::class, 'updateCashAndCheckAdjustments'])->name('update.cash.check');
        Route::put('update/check/checks', [AdjustmentController::class, 'updateCheckForAdjustments'])->name('update.check.checks');
        Route::put('cancel/check-adjustments/checks', [AdjustmentController::class, 'cancelCheckAdjustments'])->name('cancel.check.adjustments');
        Route::get('alta-cita/checks', [AdjustmentController::class, 'altaCittaCheckAdjustments'])->name('alta-citta.adjustments');
    });
    //Checks Receiving
    Route::prefix('checkreceiving')->group(function () {
        Route::get('check/for/clearing', [CheckReceivingController::class, 'getCheckForClearing'])->name('check_for.clearing');
        Route::get('search/dated', [CheckReceivingController::class, 'searchDatedChecks'])->name('search_dated');
        Route::get('check/uncheck', [CheckReceivingController::class, 'checkAndUncheck'])->name('checkUncheck.checks');
        Route::post('save/dated/leasing/pdc/checks', [CheckReceivingController::class, 'savedDatedLeasingpPdcChecks'])->name('datedleaspdc.checks');
        Route::get('pdccheck/clearing', [CheckReceivingController::class, 'pdcChecksCLearing'])->name('pdc_clearing.checks');
        Route::get('leasing/checks', [CheckReceivingController::class, 'getLeasingChecks'])->name('leasing.checks');
    });
    //Transaction
    Route::prefix('transaction')->group(function () {
        Route::get('check/manual/entry', [AllTransactionController::class, 'getCheckManualEntry'])->name('manual_entry.checks');
        Route::get('create/merge/checks', [AllTransactionController::class, 'createMergeChecks'])->name('create.merge.checks');
        Route::post('check/manual/entry/store', [AllTransactionController::class, 'checkManualEntryStore'])->name('manual_entry_store.checks');
        Route::put('update-switch', [AllTransactionController::class, 'isCheckUncheckMerge'])->name('update.merge.switch');
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
        Route::post('bounce/redeposit/replacement', [AllTransactionController::class, 'bounceCheck ReDeposit'])->name('bounceReDeposit.replace');
        Route::post('bounce/partial/cash', [AllTransactionController::class, 'bouncePartialPaymentCash'])->name('bouncePartialCash.replace');
        Route::post('bounce/partial/check', [AllTransactionController::class, 'bouncePartialReplaymentCheck'])->name('bouncePartialCheck.replace');
    });
    //Accounting
    Route::prefix('accounting')->group(function () {
        Route::get('reports/accounting', [AccountingReportController::class, 'reportIndex'])->name('reports.accounting');
        Route::get('reports/datedpdcchecks', [AccountingReportController::class, 'innerReportDatedPdcCheques'])->name('datedpcchecks.accounting');
        Route::get('start/generatng/report/accounting', [AccountingReportController::class, 'startGeneratingAccountingReports'])->name('start.generate.rep.accouting');
        Route::get('reports/deposited/checks', [AccountingReportController::class, 'innerDepositedCheckReports'])->name('deposited.reports.accounting');
        Route::get('start/generatng/deposited/accounting', [AccountingReportController::class, 'startGeneratingDepositedAccountingReports'])->name('start.generate.rep,deposited.accouting');
        Route::get('bounce/checks/report/accounting', [AccountingReportController::class, 'bounceCheckReportAccounting'])->name('bounce.checks.accounting');
        Route::get('start/generating/bounce/report/accounting', [AccountingReportController::class, 'startGeneratingReportsChequesAccounting'])->name('start.bounce.accounting');
        Route::get('redeem/check/accounting/report', [AccountingReportController::class, 'redeemPdcCheckAccountingReports'])->name('redeem.reports.accounting');
        Route::get('start/generating/redpdc/report', [AccountingReportController::class, 'startGeneratingRedeemPdcAccounting'])->name('start.generating.redpdc.accounting');
        Route::get('details/bounce', [AccountingReportController::class, 'tableBounceReports'])->name('bounce.details');
    });
    //App Settings
    Route::prefix('appsettings')->group(function () {
        Route::get('app/config', [AppSettingController::class, 'appConfigIndex'])->name('app.config');
        Route::put('update/settings', [AppSettingController::class, 'updateSettings'])->name('update.settings');
        Route::put('update/insti', [AppSettingController::class, 'updateInsti'])->name('update.inst');
    });

    //Details Controller

    Route::prefix('details')->group(function () {
        Route::get('checks/{id}', [DetailsController::class ,'details'])->name('get.check.details');
    });
    Route::get('/download/excel/{filename}', function ($filename) {
        $filePath = storage_path('app/' . $filename);
        return response()->download($filePath);
    })->name('download.excel');


    Route::prefix('credetials')->group(function () {
        Route::post('add-customer', [AddCredentialController::class, 'addCustomer'])->name('add.customer');
        Route::post('add-bank', [AddCredentialController::class, 'addBank'])->name('add.bank');
        Route::post('add-class', [AddCredentialController::class, 'addClass'])->name('add.class');
    });

    Route::put('reset/password', [AuthenticatedSessionController::class, 'newPassword'])->name('new.password');

});

require __DIR__ . '/auth.php';
