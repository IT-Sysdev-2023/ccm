<?php
namespace App\Services;

use App\Events\ExcelGenerateEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Date;


class TransactionService
{
    protected $record;
    protected bool $status; 

    protected $generateReportHeader = collect(
        [
            "NO",
            "CUSTOMER NAME",
            "CHECK NO",
            "CHECK DATE",
            "AMOUNT",
            "ACCOUNT NO",
            "ACCOUNT NAME",
            "BANK NAME",
        ]
    );

    function record(object $data): self
    {
        $this->record = $data;

        return $this;
    }
    public function headername()
    {
        return $this;
    }

    public function setStatus(bool $status){
        $this->status = $status;
        return $this;
    }
    


    public function writeResult(array $dateRange)
    {

        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '-1');
        set_time_limit(3600);

        $generate_date = today()->toFormattedDateString();
        $countTable = 1;
        $countTable1 = 0;
        $grandTotal = 0;
        $excel_row = 5;

        $status = $this->status;

        if ($status == '1') {
            $headerTitle = 'Dated Check Report';
            $headerRow = $this->generateReportHeader;
        } else {
            $headerTitle = 'Post Dated Check Report';
            $headerRow = $this->generateReportHeader->concat(['STATUS']);
        }

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()->getCell('E1')->setValue('Status Type : ' . ' ' . $headerTitle);
        $spreadsheet->getActiveSheet()->getCell('E2')->setValue('Date : ' . ' ' . $generate_date);

        $spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);

        $excel_row = 4;

        $this->record->each(function ($item, $department) use (&$spreadsheet, &$excel_row, &$headerRow, &$status, $countTable, &$grandTotal) {
            $countTable = 1;

            $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row, $department);
            // Merge cells for the department name
            $spreadsheet->getActiveSheet()->mergeCells('A' . $excel_row . ':I' . $excel_row);

            // Center align the department name
            $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getFont()->setBold(true);
            // Set header row
            $headerRowIndex = $excel_row++;
            $spreadsheet->getActiveSheet()->fromArray($headerRow->toArray(), null, 'A' . $headerRowIndex);
            $spreadsheet->getActiveSheet()->getStyle('A' . $headerRowIndex . ':I' . $headerRowIndex)->getFont()->setBold(true);

            if ($status === '1') {
                $spreadsheet->getActiveSheet()->getStyle('A' . $headerRowIndex . ':H' . $headerRowIndex)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

            } else if ($status === '2') {
                $spreadsheet->getActiveSheet()->getStyle('A' . $headerRowIndex . ':I' . $headerRowIndex)->applyFromArray([
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
            $excel_row += 2;

            $reportCollection = [];
            $subtotal = 0;

            $item->each(function ($value, $key) use ($status, &$countTable, &$subtotal, &$reportCollection, $department, $item) {
                $statusType = ''; // Reset status type for each value

                if ($status == '2') {
                    $statusType = 'POST-DATED';
                    if ($value->check_date <= date('Y-m-d')) {
                        $statusType = 'POST-DATED DUE';
                    }
                }
                // Add data for each value to the report collection
                $reportCollection[] = [
                    $countTable,
                    $value->fullname,
                    $value->check_no,
                    date('M-d-Y', strtotime($value->check_type)),
                    number_format($value->check_amount, 2),
                    $value->account_no,
                    $value->account_name,
                    $value->bankbranchname,
                    $statusType
                ];

                $subtotal += $value->check_amount;

                $countTable++;
                ExcelGenerateEvents::dispatch($department, 'Generating Excel', $countTable, $item->count(), Auth::user());
            });

            // dump($item);




            $spreadsheet->getActiveSheet()->setCellValue('E' . ($excel_row + count($item) + 1), 'Subtotal:');
            $spreadsheet->getActiveSheet()->setCellValue('F' . ($excel_row + count($item) + 1), number_format($subtotal, 2));

            $spreadsheet->getActiveSheet()->getStyle('E' . ($excel_row + count($item) + 1))->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('F' . ($excel_row + count($item) + 1))->getFont()->setBold(true);

            $spreadsheet->getActiveSheet()->fromArray($reportCollection, null, "A$excel_row");


            if ($status === '1') {
                foreach (range('A3', 'H3') as $column) {
                    $cellAddress = $column . $excel_row;
                    $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
                    $spreadsheet->getActiveSheet()->getStyle($cellAddress)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
            } else if ($status === '2') {
                foreach (range('A3', 'I3') as $column) {
                    $cellAddress = $column . $excel_row;
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

            // Set borders for the data
            $highestRow = $excel_row + count($item); // Determine the last row of data for this department
            if ($status == '1') {
                $highestColumn = 'H';
            } else {
                $highestColumn = 'I';
            } // Assuming data goes up to column I
            $dataRange = "A$excel_row:$highestColumn$highestRow";

            $spreadsheet->getActiveSheet()->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
            $spreadsheet->getActiveSheet()->getStyle($dataRange)->getFont()->setName('Fira Sans')->setSize(9);
            $grandTotal += $subtotal;

            $excel_row += count($item) + 5; // Increment row number for the next department
            // dump($countTable1);


        });

        // dump($countTable);

        $spreadsheet->getActiveSheet()->setCellValue('E' . ($excel_row), 'Grand Total:');
        $spreadsheet->getActiveSheet()->setCellValue('F' . ($excel_row), number_format($grandTotal, 2));
        $spreadsheet->getActiveSheet()->getStyle('E' . ($excel_row))->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('F' . ($excel_row))->getFont()->setBold(true);

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = $headerTitle . ' on ' . now()->format('M, d Y') . '.xlsx';


        // ExcelGenerateEvents::dispatch('assad', 'Generating Excel', 1, 2, Auth::user());
        return response()->download($tempFilePath, $filename);
        // return response()->json(['t']);

    }

    public function writeResultDuePdc(array $dateRange, $businessUnit)
    {

        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '-1');
        set_time_limit(3600);

        $dueReportData = [];


        $spreadsheet = new Spreadsheet();


        // dd($dateRange);
        if (!empty($dateRange[0]) && !empty($dateRange[1])) {
            $dueReportData = $this->record;
            $spreadsheet->getActiveSheet()->getCell('B2')->setValue('From : ' . Date::parse($dateRange[0])->toFormattedDateString() . ' To: ' . Date::parse($dateRange[1])->toFormattedDateString());
        } else {
            $spreadsheet->getActiveSheet()->getCell('B2')->setValue('From : ' . today()->toFormattedDateString());
            $dueReportData = $this->record;
        }

        $spreadsheet->getActiveSheet()->getCell('B1')->setValue('Status Type : ' . ' ' . $businessUnit->bname);

        $spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('A5:Q5')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ],
            ],
        ]);
        $table_columns = array(
            "NO.",
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

        $column = 1;

        $countTable = 1;
        $row = 6;

        foreach ($dueReportData as $value) {
            $deposited_status = DB::table('new_ds_checks')
                ->where('checks_id', '=', $value->checks_id)
                ->select('ds_no', 'status', 'date_deposit')
                ->first();

            $ds_number = '';
            $deposit_date = '';
            $days = '';

            if ($deposited_status === null) {
                $deposited_status = 'PENDING DEPOSIT';
            } else if ($deposited_status->status === 'BOUNCED') {
                $bounce_status = DB::table('new_bounce_check')
                    ->where('checks_id', '=', $value->checks_id)
                    ->orderBy('new_bounce_check.id', 'desc')
                    ->first();

                if ($bounce_status->status === 'SETTLE CHECK') {
                    $replacement_type = db::table('new_check_replacement')
                        ->where('bounce_id', $bounce_status->id)
                        ->orderBy('new_check_replacement.date_time', 'desc')
                        ->first();

                    if ($replacement_type->mode == 'RE-DEPOSIT') {
                        $redeposited_status = DB::table('new_ds_checks')
                            ->where('checks_id', '=', $value->checks_id)
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
                ->where('checks_id', $value->checks_id)
                ->first();

            if ($redeem == null) {

            } else {
                if ($redeem->status == 'REDEEMED') {
                    $deposited_status = 'REDEEMED';
                }
            }

            $datetime1 = strtotime($value->check_date);
            $datetime2 = strtotime($value->check_received);

            $secs = $datetime1 - $datetime2;
            $days = $secs / 86400;



            $reportCollection[] = [
                $countTable,
                date('m-d-Y', strtotime($value->check_received)),
                date('m-d-Y', strtotime($value->check_date)),
                $value->bankbranchname,
                $value->account_no,
                $value->account_name,
                $value->fullname,
                $value->approving_officer,
                $value->check_class,
                $value->check_category,
                $value->department,
                $value->check_no,
                number_format($value->check_amount, 2),
                $deposited_status,
                $ds_number,
                $deposit_date,
                $days,
            ];

            $countTable++;

            $spreadsheet->getActiveSheet()->fromArray($reportCollection, null, "A$row");

        }

        foreach (range('A3', 'Q3') as $column) {
            $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }

        $highestRow = $row + count($dueReportData); // Determine the last row of data for this department

        $highestColumn = 'Q';

        $dataRange = "A$row:$highestColumn$highestRow";

        $spreadsheet->getActiveSheet()->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);


        $spreadsheet->getActiveSheet()->fromArray($table_columns, null, 'A5');


        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = $businessUnit->bname . ' on ' . now()->format('M, d Y') . '.xlsx';


        return response()->download($tempFilePath, $filename);
    }




}
