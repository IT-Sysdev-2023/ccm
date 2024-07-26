<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\NewBounceCheck;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use App\Services\BounceChequesAccountingReportService;
use App\Services\DatedPdcCheckExcelServices;
use App\Services\DepositedChecksServices;
use App\Services\RedeemPdcReportServices;
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

        $data = NewSavedChecks::join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->where('department.department', 'like', '%' . $request->dataFrom . '%') // Assuming 'department' refers to 'department_name'
            ->whereAny([
                'checks.check_no',
                'checks.check_amount',
                'banks.bankbranchname',
            ], 'like', '%' . $request->search . '%') // Added 'like' operator
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->when($request->dataType == '1', function ($query) {
                $query->where('check_date', '<=', DB::raw('check_received'));
            })
            ->when($request->dataType == '2', function ($query) {
                $query->where('check_date', '>', DB::raw('check_received'));
            })
            ->when($request->dataStatus == '1', function ($query) {
                $query->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('new_ds_checks')
                        ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                });
            })
            ->when($request->dataStatus == '2', function ($query) {
                $query->whereExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('new_ds_checks')
                        ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                });
            })
            ->when($request->dateRange && !empty($request->dateRange[0]) && $request->dateRange[0] != 'Invalid Date', function ($query) use ($request) {
                $query->whereBetween('checks.check_received', [$request->dateRange[0], $request->dateRange[1]]);
            })
            ->paginate(10)
            ->withQueryString();

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
        // dd($request->dateRange);
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

        return (new DatedPdcCheckExcelServices())->record($data)->writeResult($request->dataFrom, $request->dataStatus, $request->dateRange, $request->dataType, $bunit, $request->redAcct);
    }
    public function innerDepositedCheckReports(Request $request)
    {

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        // dd($request->dateRange);
        $dt_from = $request->dateRange[0] ?? null;
        $dt_to = $request->dateRange[1] ?? null;

        $data = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('users', 'users.id', '=', 'new_ds_checks.user')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->whereAny([
                'new_ds_checks.ds_no',
                'users.name',
            ], 'like', '%' . $request->search . '%')
            ->where('status', '=', '')
            ->select('new_ds_checks.date_deposit', 'ds_no', (DB::raw('sum(check_amount) as sum')), 'name')
            ->whereBetween('new_ds_checks.date_deposit', [$dt_from, $dt_to])
            ->groupBy('date_deposit', 'ds_no', 'name')
            ->paginate(10)->withQueryString();


        return Inertia::render('AccountingReports/InnerReports/AccountingDepositedCheckReports', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$innertDepReportsColumns,
            'filters' => $request->only([
                'search',
                'dateRange',
            ]),
        ]);
    }

    public function tableBounceReports(Request $request)
    {
        $data = NewDsChecks::select(
            'new_ds_checks.*',
            'customers.*',
            'checks.check_date',
            'checks.check_no',
            'checks.check_amount',
            'checks.check_class',
            'checks.check_status',
            'checks.account_name',
            'checks.account_no',
            'checks.check_received',
            'checks.check_category',
            'checks.check_type',
            'checks.approving_officer',
            'banks.bankbranchname',
            'department.department',
        )
            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->Where('ds_no', 'like', '%' . $request->ds_no . '%')
            ->where('businessunit_id', $request->bunit)
            ->Where('new_ds_checks.date_deposit', 'like', '%' . $request->date . '%')
            ->where('status', '')
            ->get();

        return response()->json($data);
    }
    public function startGeneratingDepositedAccountingReports(Request $request)
    {

        $dt_from = $request->dateRange[0] ?? null;
        $dt_to = $request->dateRange[1] ?? null;


        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = NewDsChecks::select('new_ds_checks.date_deposit', 'ds_no', (DB::raw('sum(check_amount) as sum')), 'name')
            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('users', 'users.id', '=', 'new_ds_checks.user')
            ->whereBetween('new_ds_checks.date_deposit', [$dt_from, $dt_to])
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('status', '=', '')
            ->groupBy('date_deposit', 'ds_no', 'name')
            ->get();



        return (new DepositedChecksServices)->record($data)->writeResult($request->dateFrom, $request->dateTo, $bunit);
    }
    public function bounceCheckReportAccounting(Request $request)
    {
        // dd($request->all());
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = [];

        if ($request->dateRange == null && ($request->status == 0 || $request->status == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->whereFilter($request->search)
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($request->dateRange != null && ($request->status == 0 || $request->status == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereBetween('new_bounce_check.date_time', [$request->dateRange])
                ->whereFilter($request->search)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();

        } elseif ($request->dateRange == null && $request->status == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereFilter($request->search)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($request->dateRange == null && $request->status == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereFilter($request->search)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($request->dateRange != null && $request->status == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->whereFilter($request->search)
                ->whereBetween('new_bounce_check.date_time', [$request->dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($request->dateRange != null && $request->status == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereFilter($request->search)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$request->dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        }
        $data->map(function ($item) {
            $item->status = empty($item->status) ? 'PENDING' : $item->status;
            return $item;
        });
        // dd($request->all());
        return Inertia::render('AccountingReports/InnerReports/AccountingBounceCheckReports', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$innerBounceCheckRepAccounting,
            'filters' => $request->only([
                'search',
                'dateRange',
                'status'
            ]),
        ]);
    }
    public function startGeneratingReportsChequesAccounting(Request $request)
    {

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();
        // dd($request->all());

        if ($request->dateRange == null && ($request->status == 0 || $request->status == null)) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        } elseif ($request->dateRange != null && ($request->status == 0 || $request->status == null)) {

            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereBetween('new_bounce_check.date_time', [$request->dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        } elseif ($request->dateRange == null && $request->status == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        } elseif ($request->dateRange == null && $request->status == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        } elseif ($request->dateRange != null && $request->status == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->whereBetween('new_bounce_check.date_time', [$request->dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        } elseif ($request->dateRange != null && $request->status == "2") {
            // dd($request->dateRange);
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->user()->businessunit_id)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$request->dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->get();
        }

        $data->map(function ($item) {
            $item->status = empty($item->status) ? 'PENDING' : $item->status;
            return $item;
        });

        return (new BounceChequesAccountingReportService)->record($data)->writeResult($bunit, $request->dateRange, $request->status);
    }

    public function redeemPdcCheckAccountingReports(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('new_check_replacement.status', '=', 'REDEEMED')
            ->whereAny([
                'checks.check_no',
                'checks.check_amount',
                'customers.fullname',
            ], 'like', '%' . $request->search . '%')
            ->whereBetween('new_check_replacement.date_time', [$request->dateFrom, $request->dateTo])
            ->select('*', 'new_check_replacement.date_time')
            ->paginate(10)->withQueryString();

        return Inertia::render('AccountingReports/InnerReports/RedeemPdcCheckReports', [
            'bunit' => $bunit,
            'data' => $data,
            'filters' => $request->only(['search']),
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
            ->first();

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
        return (new RedeemPdcReportServices)->record($data)->writeResult($dateRange, $bunit, $request->reDirect);
    }
}
