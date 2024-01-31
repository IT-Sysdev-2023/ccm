<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\BusinessUnit;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

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
     
        
        $q = DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->whereBetween('checks.check_received', [$request->dt_from, $request->dt_to])
            ->where('businessunit_id', $request->bu)
            ->where('checks.check_no', 'like', '%' . $request->search . '%');


        $q = match ($request->ch_type) {
            '1' => $q->where('check_date', '<=', DB::raw('check_received')),
            '2' => $q->where('check_date', '>', DB::raw('check_received'))
        };

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
        set_time_limit(3600);

       	$bname = DB::table('businessunit')
			->where('businessunit_id', '=', $request->bu)
			->first();



          $q = DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->whereBetween('checks.check_received', [$request->dt_from, $request->dt_to])
            ->where('businessunit_id', $request->bu)
            ->where('checks.check_no', 'like', '%' . $request->search . '%');


        $q = match ($request->ch_type) {
            '1' => $q->where('check_date', '<=', DB::raw('check_received')),
            '2' => $q->where('check_date', '>', DB::raw('check_received'))
        };

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

        $data = $q->get();


        $spreadsheet = new Spreadsheet();

        $title = '';


      if ($request->ch_type == 1) {
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
		} else if ($request->ch_type == 2) {
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


        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);


        $row = 2;



        foreach ($data as $report) {

            $reportData = [
                $report->check_date,
                $report->check_received,
                $report->bankbranchname,
                $report->account_no,
                $report->account_name,
                $report->fullname,
                $report->approving_officer,
                $report->check_class,
                $report->check_category,
                $report->department,
                $report->check_no,
                number_format($report->check_amount, 2),
                // $deposited_status,
                // $ds_number,
                // $deposit_date,
            ];

            $spreadsheet->getActiveSheet()->fromArray($reportData, null, "A$row");

            foreach (range('A', 'O') as $column) {
                $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);

                // Apply border to each cell
                $spreadsheet->getActiveSheet()->getStyle($column . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }

            $row++;
        }

        // foreach ($data as $report) {
        //     $ds_number = '';
        //     $deposit_date = '';

        //     $deposited_status = DB::table('new_ds_checks')
        //         ->where('checks_id', '=', $report->checks_id)
        //         ->select('ds_no', 'status', 'date_deposit')
        //         ->first();

        //     if ($deposited_status === null) {
        //         $deposited_status = 'PENDING DEPOSIT';
        //     } else if ($deposited_status->status === 'BOUNCED') {
        //         $bounce_status = DB::table('new_bounce_check')
        //             ->where('checks_id', '=', $report->checks_id)
        //             ->orderBy('new_bounce_check.id', 'desc')
        //             ->whereNotNull('status')
        //             ->first();
        //         if ($bounce_status->status == 'SETTLED CHECK') {
        //             $replacement_type = DB::table('new_check_replacement')
        //                 ->where('bounce_id', $bounce_status->id)
        //                 ->orderBy('new_check_replacement.date_time', 'desc')
        //                 ->first();
        //             if ($replacement_type->mode == 'RE-DEPOSIT') {
        //                 $redeposited_status = DB::table('new_ds_checks')
        //                     ->where('checks_id', '=', $report->checks_id)
        //                     ->select('ds_no', 'status', 'date_deposit')
        //                     ->orderBy('id', 'desc')
        //                     ->first();
        //                 $deposited_status = $replacement_type->mode . ' CLEARED';
        //                 $ds_number = $redeposited_status->ds_no;
        //                 $deposit_date = $redeposited_status->date_deposit;
        //             } else {
        //                 $deposited_status = 'REPLACED TO ' . $replacement_type->mode;
        //             }
        //         } else {
        //             $deposited_status = 'BOUNCE PENDING CHECK';
        //         }

        //     } else {
        //         $ds_number = $deposited_status->ds_no;
        //         $deposit_date = $deposited_status->date_deposit;
        //         $deposited_status = 'CHECK CLEARED';
        //     }

        //     $redeem = DB::table('new_check_replacement')
        //         ->where('checks_id', $report->checks_id)
        //         ->first();


        //     if ($redeem == null) {

        //     } else {
        //         if ($redeem->status == 'REDEEMED') {
        //             $deposited_status = 'REDEEMED';
        //         }
        //     }



        //     $reportData = [
        //         $report->check_date,
        //         $report->check_received,
        //         $report->bankbranchname,
        //         $report->account_no,
        //         $report->account_name,
        //         $report->fullname,
        //         $report->approving_officer,
        //         $report->check_class,
        //         $report->check_category,
        //         $report->department,
        //         $report->check_no,
        //         number_format($report->check_amount, 2),
        //         $deposited_status,
        //         $ds_number,
        //         $deposit_date,
        //     ];

        //     $spreadsheet->getActiveSheet()->fromArray($reportData, null, "A$row");

        //     foreach (range('A', 'O') as $column) {
        //         $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);

        //         // Apply border to each cell
        //         $spreadsheet->getActiveSheet()->getStyle($column . $row)->applyFromArray([
        //             'borders' => [
        //                 'allBorders' => [
        //                     'borderStyle' => Border::BORDER_THIN,
        //                 ],
        //             ],
        //         ]);
        //     }

        //     $row++;
        // }

       $dt_to =  Carbon::parse($request->dt_to);
       $dt_from =  Carbon::parse($request->dt_from);

       $dt_to_f = $dt_to->format('M d, Y');
       $dt_from_f = $dt_from->format('M d, Y');




        $spreadsheet->getActiveSheet()->fromArray($headerRow, null, 'A1');

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = $bname->bname . $title . ' from ' . $dt_from_f . ' to ' . $dt_to_f .' as of ' . now()->format('M, d Y') . '.xlsx';

        // Download the file
        return response()->download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }
}
