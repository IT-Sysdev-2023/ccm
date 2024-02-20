<?php

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

Route::get('/admin/dashboard', function () {
    return Inertia::render('AdminDashboard');
})->middleware(['auth', 'verified'])->name('admin_dashboard');
Route::get('/accounting/dashboard', function () {
    return Inertia::render('AccountingDashboard');
})->middleware(['auth', 'verified'])->name('accounting_dashboard');
Route::get('/treasury/dashboard', function () {
    return Inertia::render('TreasuryDashboard');
})->middleware(['auth', 'verified'])->name('treasury_dashboard');

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
    Route::post('/tag_check_bounce', [DsBounceTaggingController::class, 'tag_check_bounce'])->name('tag_check_bounce');
    Route::post('/submit-ds-tagging', [DsBounceTaggingController::class, 'submiCheckDs'])->name('submit.ds.tagging');
    Route::put('update-switch', [DsBounceTaggingController::class, 'updateSwitch'])->name('update.switch');

    Route::get('dated_checks', [DatedPdcChecksController::class, 'dated_pdc_index'])->name('dated.checks');
});

require __DIR__ . '/auth.php';
