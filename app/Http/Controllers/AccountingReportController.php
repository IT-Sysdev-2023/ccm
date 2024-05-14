<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\NewBounceCheck;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use App\Services\BounceChequesAccountingReportService;
use App\Services\RedeemPdcAccountingReportServices;
use App\Services\DatedPdcCheckServices;
use App\Services\DepositedChecksServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AccountingReportController extends Controller
{
    //
    public function reportIndex()
    {
        return Inertia::render('AccountingReports/ReportIndex');
    }
    public function innerReportDatedPdcCheques(Request $request)
    {
        $department_from = Checks::select('department_from', 'department')
            ->leftJoin('department', 'department.department_id', '=', 'checks.department_from')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->orderBy('department')
            ->get()
            ->groupBy('department_from');

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        if (empty($request->all())) {
            $data = [];
        } else {
            $data = NewSavedChecks::join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('department', 'like', '%' . $request->dataFrom . '%')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where(function ($query) use ($request) {
                    if ($request->dataType == 1) {
                        $query->where('check_date', '<=', DB::raw('check_received'));
                    } elseif ($request->dataType == 2) {
                        $query->where('check_date', '>', DB::raw('check_received'));
                    } else {

                    }
                })
                ->where(function ($query) use ($request) {
                    if ($request->dataStatus == '1') {
                        $query->whereNotExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('new_ds_checks')
                                ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                        });
                    } elseif ($request->dataStatus == 2) {
                        $query->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('new_ds_checks')
                                ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                        });
                    }
                })
                ->where(function ($query) use ($request) {
                    if ($request->dateRange && $request->dateRange[0] != null && $request->dateRange[0] != 'Invalid Date') {

                        $query->whereBetween('checks.check_received', [$request->dateRange[0], $request->dateRange[1]]);
                    } else {
                        $query;
                    }
                })
                ->paginate(10)->withQueryString();
        }

        return Inertia::render('AccountingReports/InnerReports/DatedPostDatedChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$acc_dated_pdc_reports,
            'department_from' => $department_from,
            'bunit' => $bunit,
            'dataTypeBackend' => $request->dataType,
            'dataStatusBackend' => $request->dataStatus,
            'dataFromBackend' => $request->dataFrom,
            'dataRangeBackend' => empty($request->dateRange) || $request->dateRange[0] == 'Invalid Date' ? null : $request->dateRange,
        ]);

    }
    public function startGeneratingAccountingReports(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = NewSavedChecks::join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->where('department', 'like', '%' . $request->dataFrom . '%')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->where(function ($query) use ($request) {
                if ($request->dataType == '1') {
                    $query->where('check_date', '<=', DB::raw('check_received'));
                } elseif ($request->dataType == '2') {
                    $query->where('check_date', '>', DB::raw('check_received'));
                } else {

                }
            })
            ->where(function ($query) use ($request) {
                if ($request->dataStatus == '1') {
                    $query->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('new_ds_checks')
                            ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                    });
                } elseif ($request->dataStatus == '2') {
                    $query->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('new_ds_checks')
                            ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                    });
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->dateRange && $request->dateRange[0] != null && $request->dateRange[0] != 'Invalid Date') {
                    $query->whereBetween('checks.check_received', [$request->dateRange[0], $request->dateRange[1]]);
                } else {
                    $query;
                }
            })->get();

        return (new DatedPdcCheckServices())->record($data)->writeResult($request->dataFrom, $request->dataStatus, $request->dateRange, $request->dataType, $bunit);
    }
    public function innerDepositedCheckReports(Request $request)
    {

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        // dd($request->all());

        $data = NewDsChecks::select('new_ds_checks.date_deposit', 'ds_no', (DB::raw('sum(check_amount) as sum')), 'name')
            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('users', 'users.id', '=', 'new_ds_checks.user')
            ->whereBetween('new_ds_checks.date_deposit', [$request->dateFrom, $request->dateTo])
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('status', '=', '')
            ->groupBy('date_deposit', 'ds_no', 'name')
            ->paginate(10)->withQueryString();

        return Inertia::render('AccountingReports/InnerReports/DepositedCheckReports', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$innertDepReportsColumns,
            'dateRangeBackend' => empty([$request->dateFrom, $request->dateTo]) ? null : [$request->dateFrom, $request->dateTo],
        ]);
    }
    public function startGeneratingDepositedAccountingReports(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = NewDsChecks::select('new_ds_checks.date_deposit', 'ds_no', (DB::raw('sum(check_amount) as sum')), 'name')
            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('users', 'users.id', '=', 'new_ds_checks.user')
            ->whereBetween('new_ds_checks.date_deposit', [$request->dateFrom, $request->dateTo])
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('status', '=', '')
            ->groupBy('date_deposit', 'ds_no', 'name')
            ->limit(10)
            ->get();

        return (new DepositedChecksServices)->record($data)->writeResult($request->dateFrom, $request->dateTo, $bunit);
    }
    public function bounceCheckReportAccounting(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = [];

        if ($request->dateFrom == null && ($request->bounceStatus == 0 || $request->bounceStatus == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();

        } elseif ($request->dateFrom != null && ($request->bounceStatus == 0 || $request->bounceStatus == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereBetween('new_bounce_check.date_time', [$request->dateFrom, $request->dateTo])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
            // dd(2);

        } elseif ($request->dateFrom == null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();

        } elseif ($request->dateFrom == null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();

        } elseif ($request->dateFrom != null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->whereBetween('new_bounce_check.date_time', [$request->dateFrom, $request->dateTo])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();

        } elseif ($request->dateFrom != null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$request->dateFrom, $request->dateTo])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        }

        $data->map(function ($item) {
            $item->status = empty($item->status) ? 'PENDING' : $item->status;
            return $item;
        });

        return Inertia::render('AccountingReports/InnerReports/BounceCheckReports', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$innerBounceCheckRepAccounting,
            'dateRangeBackend' => empty([$request->dateFrom, $request->dateTo]) ? null : [$request->dateFrom, $request->dateTo],
            'dataSatusBackend' => $request->bounceStatus,
        ]);
    }
    public function startGeneratingReportsChequesAccounting(Request $request)
    {
        $dateRange = [$request->dateFrom, $request->dateTo];

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        if ($request->dateFrom == null && ($request->bounceStatus == 0 || $request->bounceStatus == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();

        } elseif ($request->dateFrom != null && ($request->bounceStatus == 0 || $request->bounceStatus == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereBetween('new_bounce_check.date_time', [$request->dateFrom, $request->dateTo])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();

        } elseif ($request->dateFrom == null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();

        } elseif ($request->dateFrom == null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();

        } elseif ($request->dateFrom != null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->whereBetween('new_bounce_check.date_time', [$request->dateFrom, $request->dateTo])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();

        } elseif ($request->dateFrom != null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$request->dateFrom, $request->dateTo])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        }

        $data->map(function ($item) {
            $item->status = empty($item->status) ? 'PENDING' : $item->status;
            return $item;
        });

        return (new BounceChequesAccountingReportService)->record($data)->writeResult($bunit, $dateRange, $request->bounceStatus);
    }

    public function redeemPdcCheckAccountingReports(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = DB::table('new_check_replacement')
            ->join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('new_check_replacement.status', '=', 'REDEEMED')
            ->whereBetween('new_check_replacement.date_time', [$request->dateFrom, $request->dateTo])
            ->select('*', 'new_check_replacement.date_time')
            ->paginate(10)->withQueryString();

        return Inertia::render('AccountingReports/InnerReports/RedeemPdcCheckReports', [
            'bunit' => $bunit,
            'data' => $data,
            'columns' => ColumnsHelper::$innerRedeemPdcReportsColumns,
            'dateRangeBackend' => empty([$request->dateFrom, $request->dateTo]) ? null : [$request->dateFrom, $request->dateTo],
        ]);
    }

    public function startGeneratingRedeemPdcAccounting(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $dateRange = [$request->dateFrom, $request->dateTo];

        $data = DB::table('new_check_replacement')
            ->join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('new_check_replacement.status', '=', 'REDEEMED')
            ->whereBetween('new_check_replacement.date_time', [$request->dateFrom, $request->dateTo])
            ->select('*', 'new_check_replacement.date_time', 'new_check_replacement.id')
            ->get();
        return (new RedeemPdcAccountingReportServices)->record($data)->writeResult($dateRange, $bunit );
    }

}
