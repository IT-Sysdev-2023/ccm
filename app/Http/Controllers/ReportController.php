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
    public function datedpdcchecksreports()
    {

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')->get();

        return Inertia::render('Reports/DatedPdcReports', [
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$datedPdcReportTableColumn,
        ]);
    }
    public function get_dated_pdc_checks_rep(Request $request)
    {

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

        $data->transform(function ($item) {
            $item->check_received = Date::parse($item->check_received)->toFormattedDateString();
            $item->check_date = Date::parse($item->check_date)->toFormattedDateString();
            $item->check_amount = NumberHelper::currency($item->check_amount);
            return $item;
        });
        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }

    public function generate_reps_to_excel(Request $request)
    {

        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '-1');
        set_time_limit(3600);

        $bname = BusinessUnit::where('businessunit_id', '=', $request->bu)->first();

        $q = NewSavedChecks::joinChecksCustomerBanksDepartment()->reportQuery($request->bu, $request->search)
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
            });

        $data = $q->get();

        $spreadsheet = new Spreadsheet();

        $title = '';
        $h_type = '';

        if ($request->ch_type == 1) {
            $this->headerRow->concat(["PDC GAP(DAYS)"]);
        }

        if ($request->ch_type === '2' && $request->repporttype === '0') {
            $h_type = 'ALL DATED CHECKS';
            $title = 'AllDATEDCHECKS';
        } else if ($request->ch_type === '1' && $request->repporttype === '0') {
            $h_type = 'ALL PENDING CHECKS';
            $title = 'ALLPDC';
        } else if ($request->ch_type === '1' && $request->repporttype === '1') {
            $h_type = 'PENDING PDC';
            $title = 'PENDINGPDC';
        } else if ($request->ch_type === '1' && $request->repporttype === '2') {
            $h_type = 'PENDING DATED CHECKS';
            $title = 'PENDINGDATEDCHECKS';
        } else if ($request->ch_type === '2' && $request->repporttype === '1') {
            $h_type = 'PENDING DATED CHECKS';
            $title = 'DEPOSITEDDATEDCHECKS';
        } else if ($request->ch_type === '2' && $request->repporttype === '2') {
            $h_type = 'DEPOSITED DATED CHECKS';
            $title = 'DEPOSITEDDATEDCHECKS';
        } elseif (!$request->dt_from && !$request->dt_to && $request->ch_type === '1' && $request->repporttype === 0) {
            $h_type = 'ALL DATED CHECKS';
            $title = 'AllDATEDCHECKS';
        } else if (!$request->dt_from && !$request->dt_to && $request->ch_type === '2' && $request->repporttype === 0) {
            $h_type = 'ALL PENDING CHECKS';
            $title = 'ALLPDC';
        } else if (!$request->dt_from && !$request->dt_to && $request->ch_type === '1' && $request->repporttype === '1') {
            $h_type = 'PENDING PDC';
            $title = 'PENDINGPDC';
        } else if (!$request->dt_from && !$request->dt_to && $request->ch_type === '1' && $request->repporttype === '2') {
            $h_type = 'PENDING DATED CHECKS';
            $title = 'PENDINGDATEDCHECKS';
        } else if (!$request->dt_from && !$request->dt_to && $request->ch_type === '2' && $request->repporttype === '1') {
            $h_type = 'DEPOSITED DATED CHECKS';
            $title = 'DEPOSITEDDATEDCHECKS';
        } else if (!$request->dt_from && !$request->dt_to && $request->ch_type === '2' && $request->repporttype === '2') {
            $h_type = 'PENDING DATED CHECKS';
            $title = 'PENDINGDATEDCHECKS';
        }

        if ($request->ch_type === '1') {
            $spreadsheet->getActiveSheet()->getStyle('A5:P5')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

        } else if ($request->ch_type === '2') {
            $spreadsheet->getActiveSheet()->getStyle('A5:O5')->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);

        }

        $spreadsheet->getActiveSheet()->getStyle('E3:O3')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $spreadsheet->getActiveSheet()->getStyle('A2:O2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $row = 6;
        $column = 6;

        foreach ($data as $report) {
            $deposited_status = DB::table('new_ds_checks')
                ->where('checks_id', '=', $report->checks_id)
                ->select('ds_no', 'status', 'date_deposit')
                ->first();

            $ds_number = '';
            $deposit_date = '';
            $days = '';

            if ($deposited_status === null) {
                $deposited_status = 'PENDING DEPOSIT';
            } else if ($deposited_status->status === 'BOUNCED') {
                $bounce_status = DB::table('new_bounce_check')
                    ->where('checks_id', '=', $report->checks_id)
                    ->orderBy('new_bounce_check.id', 'desc')
                    ->first();

                if ($bounce_status->status === 'SETTLE CHECK') {
                    $replacement_type = db::table('new_check_replacement')
                        ->where('bounce_id', $bounce_status->id)
                        ->orderBy('new_check_replacement.date_time', 'desc')
                        ->first();

                    if ($replacement_type->mode == 'RE-DEPOSIT') {
                        $redeposited_status = DB::table('new_ds_checks')
                            ->where('checks_id', '=', $report->checks_id)
                            ->select('ds_no', 'status', 'date_deposit')
                            ->orderBy('id', 'desc')
                            ->first();

                        $deposited_status = $replacement_type->mode . ' CLEARED';
                        $ds_number = $redeposited_status->ds_no;
                        $deposit_date = $redeposited_status->date_deposit;
                    } else {
                        $deposited_status = 'REPLACED TO ' . $replacement_type->mode;
                    }
                } else {

                }
            } else {
                $ds_number = $deposited_status->ds_no;
                $deposit_date = $deposited_status->date_deposit;
                $deposited_status = 'CHECK CLEARED';
            }

            $redeem = DB::table('new_check_replacement')
                ->where('checks_id', $report->checks_id)
                ->first();

            if ($redeem == null) {

            } else {
                if ($redeem->status == 'REDEEMED') {
                    $deposited_status = 'REDEEMED';
                }
            }

            if ($request->ch_type === '1') {
                $datetime1 = strtotime($report->check_date);
                $datetime2 = strtotime($report->check_received);

                $secs = $datetime1 - $datetime2;
                $days = $secs / 86400;
            } else if ($request->ch_type === '2') {
                $days = '';
            }

            $reportData = [
                date('F-d-Y', strtotime($report->check_date)),
                date('F-d-Y', strtotime($report->check_received)),
                $report->bankbranchname,
                $report->account_no,
                $report->account_name,
                $report->fullname,
                $report->approving_officer,
                $report->check_class,
                $report->check_category,
                $report->department,
                $report->check_no,
                NumberHelper::currency($report->check_amount),
                $deposited_status,
                $ds_number,
                date('F-d-Y', strtotime($deposit_date)),
                $days,
            ];

            $spreadsheet->getActiveSheet()->fromArray($reportData, null, "A$row");

            if ($request->ch_type === '1') {
                foreach (range('A6', 'P6') as $column) {
                    $cellAddress = $column . $row;
                    $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                    $spreadsheet->getActiveSheet()->getStyle($cellAddress)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
            } else if ($request->ch_type === '2') {
                foreach (range('A', 'O') as $column) {
                    $cellAddress = $column . $row;
                    $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                    $spreadsheet->getActiveSheet()->getStyle($cellAddress)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
            }

            $row++;
        }

        // dd($request->dt_from, $request->dt_to);

        if (!$request->dt_from && !$request->dt_to) {
            // dd(1);
        } else {
            $dt_to = Carbon::parse($request->dt_to);
            $dt_from = Carbon::parse($request->dt_from);
            $dt_to_f = $dt_to->format('M d, Y');
            $dt_from_f = $dt_from->format('M d, Y');

        }

        $spreadsheet->getActiveSheet()->fromArray($this->headerRow->all(), null, 'A5');
        $spreadsheet->getActiveSheet()->getCell('E3')->setValue('BUSINESS UNIT : ' . ' ' . $bname->bname);
        $spreadsheet->getActiveSheet()->getCell('E1')->setValue('REPORT TYPE : ' . ' ' . $h_type);
        if (!$request->dt_from && !$request->dt_to) {
            $spreadsheet->getActiveSheet()->getCell('E2')->setValue('AS OF:  ' . strtoupper(date('F-d-Y')));
        } else {
            $spreadsheet->getActiveSheet()->getCell('E2')->setValue('FROM: ' . strtoupper(date('F-d-Y', strtotime($request->dt_from))) . '  TO: ' . strtoupper(date('F-d-Y', strtotime($request->dt_to))) . ' AS OF:  ' . strtoupper(date('F-d-Y')));
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        if (!$request->dt_from && !$request->dt_to) {
            $filename = $bname->bname . $title . '-All -' . $title . now()->format('M, d Y') . '.xlsx';
        } else {
            $filename = $bname->bname . $title . ' from ' . $dt_from_f . ' to ' . $dt_to_f . ' as of ' . now()->format('M, d Y') . '.xlsx';

        }

        // Download the file
        return response()->download($tempFilePath, $filename, [], 'inline');
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
