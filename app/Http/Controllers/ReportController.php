<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Models\BusinessUnit;
use App\Models\NewBounceCheck;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use App\Services\AltaChecksReportServices;
use App\Services\RedeemPdcReportServices;
use App\Services\ReportBounceCheckService;
use App\Services\ReportDepositedCheckService;
use App\Services\DatedPdcCheckExcelServices;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{

    private $headerRow;
    public function __construct()
    {
        $this->headerRow = collect([
            "DATE RECIEVED",
            "CHECK DATE",
            "BANK",
            "ACCOUNT NUMBER",
            "ACCOUNT NAME",
            "CUSTOMER",
            "APPROVING OFFICER",
            "CHECK CLASS",
            "CHECK CATEGORY",
            "CHECK FROM",
            "CHECK NO.",
            "AMOUNT",
            "DEPOSIT STATUS",
            "DS_NO",
            "DEPOSIT DATE",
        ]);
    }

    private static function reusableQuery($request)
    {

        $dateRange = [$request->dateRangeArr0, $request->dateRangeArr1];

        $data = [];

        if ($dateRange[0] == null && $request->bounceStatus == 0 || $request->bounceStatus == null) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->bunitCode)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($dateRange[0] != null && $request->bounceStatus == 0 || $request->bounceStatus == null) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->bunitCode)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($dateRange[0] == null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->bunitCode)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($dateRange[0] == null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($dateRange[0] != null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->whereBetween('new_bounce_check.date_time', [$dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        } elseif ($dateRange[0] != null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->paginate(10)->withQueryString();
        }

        $data->transform(function ($item) {
            $item->status = empty($item->status) ? 'PENDING' : $item->status;
            return $item;
        });

        return $data;
    }
    public function datedpdcchecksreports(Request $request)
    {
    //  dd($request->all());

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')->get();

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->reportQuery($request->bu, $request->search)
            ->when($request->ch_type == '1', function (Builder $query) {
                $query->whereColumn('check_date', '>', 'check_received');
            })
            ->when($request->ch_type == '2', function (Builder $query) {
                $query->whereColumn('check_date', '<=', 'check_received');
            })
            ->when(!is_null($request->dt_from) && !is_null($request->dt_to), function (Builder $query) use ($request) {
                $query->whereBetween('check_received', [$request->dt_from, $request->dt_to]);
            })
            ->when($request->repporttype == '1', function (Builder $query) {
                $query->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('new_ds_checks')
                        ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                });
            })
            ->when($request->repporttype == '2', function (Builder $query) {
                $query->has('dsCheck.check');
            })->paginate(10)->withQueryString();

        return Inertia::render('Reports/DatedPdcReports', [
            'data' => $data,
            'bunit' => $bunit,
            'bunitBackend' => empty($request->bu) ? null : intval($request->bu),
            'typeBackend' => $request->ch_type,
            'reportTypeBackend' => $request->repporttype,
            'dateRangeBackend' => empty([$request->dt_from, $request->dt_to]) ? null : [$request->dt_from, $request->dt_to],
            'columns' => ColumnsHelper::$datedPdcReportTableColumn,
        ]);
    }
    public function generateExcelDatedChecksPdc(Request $request)
    {

        $bname = BusinessUnit::where('businessunit_id', '=', $request->bu)->get();

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()->reportQuery($request->bu, $request->search)
            ->when($request->ch_type == '1', function (Builder $query) {
                $query->whereColumn('check_date', '>', 'check_received');
            })
            ->when($request->ch_type == '2', function (Builder $query) {
                $query->whereColumn('check_date', '<=', 'check_received');
            })
            ->when(($request->dt_from && $request->dt_to), function (Builder $query) use ($request) {
                $query->whereBetween('check_received', [$request->dt_from, $request->dt_to]);
            })
            ->when($request->repporttype == '1', function (Builder $query) {
                $query->doesntHave('dsCheck.check');
            })
            ->when($request->repporttype == '2', function (Builder $query) {
                $query->has('dsCheck.check');
            })->get();

            // dd($request->dt_from, $request->dt_to);



        return (new DatedPdcCheckExcelServices())->record($data)->writeResult($request->bu, $request->ch_type, [$request->dt_from, $request->dt_to], $request->repporttype, $bname, $request->redAdmin);



    }

    public function depositedCheckReports(Request $request)
    {

        $dateRange = [$request->dateRangeArr0, $request->dateRangeArr1];

        if ($request->user()->usertype_id == 2) {
            $buId = BusinessUnit::whereNotNull('loc_code_atp')
                ->whereNotNull('b_atpgetdata')
                ->whereNotNull('b_encashstart')
                ->where('businessunit_id', Auth::user()->businessunit_id)
                ->get();
        } else {
            $buId = BusinessUnit::whereNotNull('loc_code_atp')
                ->whereNotNull('b_atpgetdata')
                ->whereNotNull('b_encashstart')
                ->where('businessunit_id', '!=', 61)
                ->get();
        }

        $data = NewDsChecks::select('new_ds_checks.date_deposit', 'ds_no', (DB::raw('sum(check_amount) as sum')), 'name')

            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('users', 'users.id', '=', 'new_ds_checks.user')
            ->whereBetween('new_ds_checks.date_deposit', [$request->dateRangeArr0, $request->dateRangeArr1])
            ->where('checks.businessunit_id', $request->bunitId)
            ->where('status', '=', '')
            ->groupBy('date_deposit', 'ds_no', 'name')
            ->paginate(10)->withQueryString();

        return Inertia::render('Reports/DepositedCheckReports', [
            'data' => $data,
            'buData' => $buId,
            'columns' => ColumnsHelper::$deposited_checks_column,
            'dateRangeBackend' => $dateRange[0] == null ? null : $dateRange,
            'buniIdType' => $request->bunitId == '' ? null : intval($request->bunitId),
        ]);
    }
    public function startGeneratingDepositedChecks(Request $request)
    {
        $dateRange = [$request->dateRangeArr0, $request->dateRangeArr1];
        $buId = $request->bunitId;

        $data = NewDsChecks::select('new_ds_checks.date_deposit', 'ds_no', (DB::raw('sum(check_amount) as sum')), 'name')
            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('users', 'users.id', '=', 'new_ds_checks.user')
            ->whereBetween('new_ds_checks.date_deposit', [$request->dateRangeArr0, $request->dateRangeArr1])
            ->where('checks.businessunit_id', $request->bunitId)
            ->where('status', '=', '')
            ->groupBy('date_deposit', 'ds_no', 'name')
            ->cursor();

        return (new ReportDepositedCheckService())->record($data)->writeResult($buId, $dateRange);
    }
    public function bounceCheckReports(Request $request)
    {

        $dateRange = [$request->dateRangeArr0, $request->dateRangeArr1];

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', '!=', 61)
            ->cursor();

        $data = self::reusableQuery($request);

        return Inertia::render('Reports/BounceCheckReports', [
            'data' => $data,
            'bunit' => $bunit,
            'bunitCodeBackend' => $request->bunitCode == '' ? null : intval($request->bunitCode),
            'bounceValue' => $request->bounceStatus,
            'dateRangeBackend' => $dateRange[0] === null ? null : $dateRange,
            'columns' => ColumnsHelper::$bounceCheckReportColumn,
        ]);
    }
    public function startGeneratingBounceCheckReport(Request $request)
    {

        $dateRange = [$request->dateRangeArr0, $request->dateRangeArr1];

        $data = [];

        if ($dateRange[0] == null && $request->bounceStatus == 0 || $request->bounceStatus == null) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->bunitCode)
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->cursor();
        } elseif ($dateRange[0] != null && $request->bounceStatus == 0 || $request->bounceStatus == null) {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->whereBetween('new_bounce_check.date_time', [$dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->cursor();
        } elseif ($dateRange[0] == null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->cursor();
        } elseif ($dateRange[0] == null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->cursor();
        } elseif ($dateRange[0] != null && $request->bounceStatus == "1") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->where('businessunit_id', $request->bunitCode)
                ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
                ->whereBetween('new_bounce_check.date_time', [$dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->cursor();
        } elseif ($dateRange[0] != null && $request->bounceStatus == "2") {
            $data = NewBounceCheck::join('checks', 'new_bounce_check.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('businessunit_id', $request->bunitCode)
                ->where('new_bounce_check.status', 'SETTLED CHECK')
                ->whereBetween('new_bounce_check.date_time', [$dateRange])
                ->select('*', 'new_bounce_check.date_time', 'new_bounce_check.id')
                ->cursor();
        }

        $data->map(function ($item) {
            $item->status = empty($item->status) ? 'PENDING' : $item->status;
            return $item;
        });

        // dd($data->toArray());
        return (new ReportBounceCheckService())->record($data)->writeResult($request->bunitCode, $request->bounceStatus, $dateRange);
    }

    public function redeemCheckReportsAdmin(Request $request)
    {
        // dd(1);

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', '!=', 61)
            ->cursor();

        $data = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks.businessunit_id', $request->bunitId)
            ->where('new_check_replacement.status', '=', 'REDEEMED')
            ->whereBetween('new_check_replacement.date_time', [$request->dateFrom, $request->dateTo])
            ->select('*', 'new_check_replacement.date_time')
            ->paginate(10)->withQueryString();

        return Inertia::render('Reports/RedeemCheckReports', [
            'bunit' => $bunit,
            'data' => $data,
            'columns' => ColumnsHelper::$innerRedeemPdcReportsColumns,
            'bunitBackend' => $request->bunitId == null ? null : intval($request->bunitId),
            'dateRangeBackend' => empty([$request->dateFrom, $request->dateTo]) ? null : [$request->dateFrom, $request->dateTo],
        ]);

    }

    public function startGeneratingRedeemReports(Request $request)
    {
        // dd($request->all());

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->bunitId)
            ->first();

        $dateRange = [$request->dateFrom, $request->dateTo];

        $data = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks.businessunit_id', $request->bunitId)
            ->where('new_check_replacement.status', '=', 'REDEEMED')
            ->whereBetween('new_check_replacement.date_time', $dateRange)
            ->select('*', 'new_check_replacement.date_time', 'new_check_replacement.id')
            ->get();

        // dump($data);

        return (new RedeemPdcReportServices)->record($data)->writeResult($dateRange, $bunit, $request->reDirect);
    }

    public function checksInAltaReports(Request $request)
    {
        $checkFromTms = DB::connection('mysql2')
            ->table('tbl_checks2')
            ->join('tbl_sales', 'tbl_sales.sales_id', '=', 'tbl_checks2.sales_id')
            ->join('tbl_bank', 'tbl_bank.bank_id', '=', 'tbl_checks2.bank_id')
            ->where('tbl_checks2.check_date', 'LIKE', '%' . $request->altaDates . '%')
            ->where('official_ds', '!=', '')
            ->select(
                'check_id',
                'tbl_bank.bank',
                'check_num',
                'check_recieved',
                'check_date',
                'currency',
                'official_ds',
                (DB::raw('sum(tbl_checks2.amount) as amount'))
            )
            ->groupBy('official_ds', 'check_date')
            ->paginate(10)->withQueryString();

        return Inertia::render('Reports/ChecksAltaReports', [
            'data' => $checkFromTms,
            'columns' => ColumnsHelper::$alta_citaCheckColumns,
            'dateBackEnd' => empty($request->altaDates) ? null : $request->altaDates,
        ]);
    }
    public function altaCitaDetails(Request $request)
    {
        // dd(1);

        $checkTmsAlta = DB::connection('mysql2')
            ->table('tbl_checks2')
            ->join('tbl_sales', 'tbl_sales.sales_id', '=', 'tbl_checks2.sales_id')
            ->join('tbl_bank', 'tbl_bank.bank_id', '=', 'tbl_checks2.bank_id')
            ->where('check_id', '=', $request->check_id)
            ->select(
                'acc_num',
                'acc_name',
                'business_unit_id',
                'department_id',
                'emp_id'
            )
            ->first();

            // dd( $checkTmsAlta);

        $dataPis = DB::connection('pis')
            ->table('locate_business_unit')
            ->where('bcode', '=', $checkTmsAlta->business_unit_id)
            ->select(
                'business_unit',
                'bunit_code',
                'company_code'
            )
            ->first();

        $getDepFromPis = DB::connection('pis')
            ->table('locate_department')
            ->where('bunit_code', '=', $dataPis->bunit_code)
            ->where('dept_code', '=', $checkTmsAlta->department_id)
            ->where('company_code', '=', $dataPis->company_code)
            ->select(
                'dept_name'
            )
            ->first();

        $appOff = DB::connection('pis')
            ->table('employee3')
            ->where('record_no', '=', $checkTmsAlta->emp_id)
            ->select(
                'name'
            )
            ->first();

        $result= [
            'acc_num' => $checkTmsAlta->acc_num,
            'acc_name' => $checkTmsAlta->acc_name,
            'business_unit' => $dataPis->business_unit,
            'dept_name' => $getDepFromPis->dept_name,
        ];

        // dd($result);

        return response()->json($result);

    }
    public function startGenerateAltaReports(Request $request)
    {
        $data = DB::connection('mysql2')
            ->table('tbl_checks2')
            ->join('tbl_sales', 'tbl_sales.sales_id', '=', 'tbl_checks2.sales_id')
            ->join('tbl_bank', 'tbl_bank.bank_id', '=', 'tbl_checks2.bank_id')
            ->where('tbl_checks2.check_date', 'LIKE', '%' . $request->altaDates . '%')
            ->where('official_ds', '!=', '')
            ->select(
                'check_id',
                'tbl_bank.bank',
                'check_num',
                'check_recieved',
                'check_date',
                'currency',
                'official_ds',
                (DB::raw('sum(tbl_checks2.amount) as amount'))
            )
            ->groupBy('official_ds', 'check_date')
            ->get(10);

        return (new AltaChecksReportServices)->record($data)->writeResult($request->altaDates);
    }

}
