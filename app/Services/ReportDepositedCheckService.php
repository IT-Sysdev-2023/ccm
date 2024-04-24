<?php

namespace App\Services;

use App\Helper\Excel\ExcelWriter;
use Illuminate\Support\LazyCollection;
use Inertia\Inertia;
use App\Models\NewDsChecks;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportDepositedCheckService extends ExcelWriter
{

    protected LazyCollection $record;
    protected $border;
    protected $borderFBN;
    protected $singleBorderWithBgColor;

    public function __construct()
    {

        parent::__construct();

        $this->generateUserHeader = [
            "NO",
            "DATE DEPOSIT",
            "DS NUMBER",
            "USER",
        ];

        $this->generateReportHeader = array(
            [
                "NO",
                "CHECK NUMBER",
                "CHECK CLASS",
                "CHECK CATEGORY",
                "ACCOUNT NUMBER",
                "ACCOUNT NAME",
                "DATE RECEIVED",
                "DEPARTMENT",
                "BANK NAME",
                "APPROVING OFFICER",
                "CUSTOMER",
                "CHECK AMOUNT - (PHP)"
            ],
        );
        $this->border = $this->initializedBorder();
        $this->borderFBN = $this->initializedBorderFontBoldNone();
        $this->singleBorderWithBgColor = $this->singleBorderWithBgColor();

    }

    public function record(LazyCollection $data)
    {
        $this->record = $data;

        return $this;
    }

    public function writeResult($buId)
    {
        $this->executionTime();

        $spreadsheet = new Spreadsheet();
        $num = 0;
        $excelRow = 5;
        $count = 1;
        $header = $this->generateUserHeader;
        $headerBelow = $this->generateReportHeader;

        $this->record->each(function ($item) use ($buId, &$num, &$excelRow, &$header, &$count, &$headerBelow) {
            $data = NewDsChecks::select('new_ds_checks.*',
                'customers.*',
                'checks.check_received',
                'checks.check_no',
                'checks.check_amount',
                'banks.bankbranchname',
                'checks.approving_officer',
                'department',
                'checks.check_class',
                'checks.check_no',
                'checks.account_no',
                'checks.account_name',
                'checks.check_category')
                ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->join('department', 'checks.department_from', '=', 'department.department_id')
                ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                ->where('ds_no', '=', $item->ds_no)
                ->where('businessunit_id', $buId)
                ->Where('new_ds_checks.date_deposit', '=', $item->date_deposit)
                ->where('status', '')
                ->limit(10)->get();

            $reportData[] = [
               $count++,
               date('F j Y', strtotime($item->date_deposit)),
               $item->ds_no,
               $item->name,
            ];

            $this->getActiveSheetExcel()->fromArray($header, null, 'E' . $excelRow);



            foreach (range('E', 'H') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $excelRow ++;


            $this->spreadsheet->getActiveSheet()->fromArray($reportData, null, "E$excelRow");

            foreach (range('E', 'H') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $excelRow += count($reportData) + 1;

            foreach (range('A', 'L') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $this->getActiveSheetExcel()->fromArray($headerBelow, null, 'A' . $excelRow);

            $excelRow++;

            $innerCount = 1;

            $data->each(function ($item) use (&$excelRow, &$innerCount){
                $reportDataCollection[] = [
                    $innerCount++,
                    $item->check_no,
                    $item->check_class,
                    $item->check_category,
                    $item->account_no,
                    $item->account_name,
                    date('F j Y', strtotime($item->check_received)),
                    $item->department,
                    $item->bankbranchname,
                    $item->approving_officer,
                    $item->fullname,
                    number_format($item->check_amount, 2)
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($reportDataCollection, null, "A$excelRow");



                foreach (range('A', 'L') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $excelRow++;


            });
            $this->getActiveSheetExcel()->getStyle('L' . $excelRow)->applyFromArray($this->borderFBN);
            $this->getActiveSheetExcel()->getStyle('L' . $excelRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $this->getActiveSheetExcel()->getStyle('L' . $excelRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C4E4FF');
            $this->spreadsheet->getActiveSheet()->setCellValue('L' . $excelRow, 'TOTAL:     ' . number_format($item->sum, 2));

            $excelRow+= 3;



        });
        foreach (range('A', 'L') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }

        // dd($data1->toArray());

        // dd(1);

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        $filename = 'sample' . '.xlsx';
        $filePath = storage_path('app/' . $filename);

        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/ReportPartials/ResultDepositedReports', [
            'downloadExcel' => $downloadExcel,
        ]);

    }
}
