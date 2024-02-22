<?php

namespace App\Http\Controllers;

use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessUnit;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportController extends Controller
{
    public function datedpdcchecksreports()
    {

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')->get();

        return Inertia::render('Reports/DatedPdcReports', [
            'bunit' => $bunit
        ]);
    }
    public function get_dated_pdc_checks_rep(Request $request)
    {


        $q = NewSavedChecks::joinChecksCustomer()
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->where('businessunit_id', $request->bu)
            ->where('checks.check_no', 'like', '%' . $request->search . '%');

        $q = match ($request->ch_type) {
            '2' => $q->where('check_date', '<=', DB::raw('check_received')),
            '1' => $q->where('check_date', '>', DB::raw('check_received'))
        };

        if ($request->dt_from !== null && $request->dt_to !== null) {
            $q->whereBetween('check_received', [$request->dt_from, $request->dt_to]);
        } else {
            $data = $q->paginate(20)->withQueryString();
        }

        $q = match ($request->repporttype) {
            '1' => $q->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                }),
            '2' => $q->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                }),

            default => $q,
        };



        $data = $q->paginate(20)->withQueryString();


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

        $bname = DB::table('businessunit')
            ->where('businessunit_id', '=', $request->bu)
            ->first();



        $q = DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->where('businessunit_id', $request->bu)
            ->where('checks.check_no', 'like', '%' . $request->search . '%');

        $q = match ($request->ch_type) {
            '2' => $q->where('check_date', '<=', DB::raw('check_received')),
            '1' => $q->where('check_date', '>', DB::raw('check_received'))
        };

        if (!$request->dt_from && !$request->dt_to) {
            $data = $q;
        } else {
            $q->whereBetween('check_received', [$request->dt_from, $request->dt_to]);
        }

        $q = match ($request->repporttype) {
            '1' => $q->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                }),
            '2' => $q->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                }),
            '0' => $q,
        };



        $data = $q->get();


        $spreadsheet = new Spreadsheet();

        $title = '';
        $h_type = '';


        if ($request->ch_type == 2) {
            $headerRow = array(
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
            );
        } else if ($request->ch_type == 1) {
            $headerRow = array(
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
                "PDC GAP(DAYS)",
            );
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
                    $deposited_status = 'BOUNCE PENDING CHECK';
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
            } else if ($request->ch_type === '1') {
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
                '₱' . number_format($report->check_amount, 2),
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






        $spreadsheet->getActiveSheet()->fromArray($headerRow, null, 'A5');
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
        return response()->download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }
}
