<?php

namespace App\Services;

use App\Events\DepositedCheckReportsAccounting;
use App\Helper\Excel\ExcelWriter;
use App\Models\NewDsChecks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DepositedChecksServices extends ExcelWriter
{
    protected $record;
    protected $generateUserHeader;
    protected $border;
    protected $borderFBN;
    protected $generateReportHeader;
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
                "CHECK AMOUNT - (PHP)",
            ],
        );
        $this->border = $this->initializedBorder();
        $this->borderFBN = $this->initializedBorderFontBoldNone();
        $this->singleBorderWithBgColor = $this->singleBorderWithBgColor();

    }

    public function record($data)
    {
        $this->record = $data;

        return $this;
    }

    public function writeResult($dateFrom, $dateTo, $bunit)
    {
        $this->executionTime();

        $spreadsheet = new Spreadsheet();
        $num = 0;
        $excelRow = 5;
        $bunitRow = 2;
        $count = 1;
        $header = $this->generateUserHeader;
        $headerBelow = $this->generateReportHeader;
        $progressCount = 1;
        $itemCount = count($this->record);
        $buId = $bunit[0]->businessunit_id;

        $bunitHeader = $bunit[0]->bname;

        $this->getActiveSheetExcel()->setCellValue('B' . $bunitRow, $bunitHeader);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0B60B0');
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->getColor()->setRGB('0B60B0');
        $bunitRow++;

        $this->getActiveSheetExcel()->setCellValue('B' . $bunitRow, 'DEPOSITED CHECKS');
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0B60B0');
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->getColor()->setRGB('0B60B0');
        $bunitRow++;

        // Set cell value for "Date From" in bold font and center align horizontally
        $this->getActiveSheetExcel()->setCellValue('B' . $bunitRow, 'Date From: ' . date('F j Y', strtotime($dateFrom)));
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0B60B0');
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->getColor()->setRGB('0B60B0');
        // Set cell value for "to" and "Date To" in bold font and center align horizontally
        $this->getActiveSheetExcel()->setCellValue('C' . $bunitRow, 'to');
        $this->getActiveSheetExcel()->getStyle('C' . $bunitRow)->getFont()->getColor()->setRGB('0B60B0');
        $this->getActiveSheetExcel()->getStyle('C' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('C' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $this->getActiveSheetExcel()->getStyle('C' . $bunitRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0B60B0');
        $this->getActiveSheetExcel()->setCellValue('D' . $bunitRow, 'Date To: ' . date('F j Y', strtotime($dateTo)));
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0B60B0');
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getFont()->getColor()->setRGB('0B60B0');
        $bunitRow++;

        $this->record->each(function ($item) use ($itemCount, $buId, &$num, &$excelRow, &$header, &$count, &$headerBelow, &$progressCount) {

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
                $fillColor = [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '68D2E8'],
                ];
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
            }
            $excelRow++;

            $this->spreadsheet->getActiveSheet()->fromArray($reportData, null, "E$excelRow");

            foreach (range('E', 'H') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
            }

            $excelRow += count($reportData) + 1;

            foreach (range('A', 'L') as $col) {
                $cell = $col . $excelRow;

                $fillColor = [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F1F1F1'],
                ];
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
            }
            $this->getActiveSheetExcel()->fromArray($headerBelow, null, 'A' . $excelRow);

            $excelRow++;

            $innerCount = 1;

            $data->each(function ($item) use (&$excelRow, &$innerCount) {
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
                    number_format($item->check_amount, 2),
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($reportDataCollection, null, "A$excelRow");

                foreach (range('A', 'L') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow++;

            });
            $this->getActiveSheetExcel()->getStyle('L' . $excelRow)->applyFromArray($this->borderFBN);
            $this->getActiveSheetExcel()->getStyle('L' . $excelRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $this->getActiveSheetExcel()->getStyle('L' . $excelRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C4E4FF');
            $this->spreadsheet->getActiveSheet()->setCellValue('L' . $excelRow, 'TOTAL:     ' . number_format($item->sum, 2));

            $excelRow += 3;

            DepositedCheckReportsAccounting::dispatch('Generating Deposited Checks ', ++$progressCount, $itemCount, Auth::user());
        });
        foreach (range('A', 'L') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        $filename = $bunitHeader . ' Report from ' . date('F j Y', strtotime($dateFrom)) . ' to ' . date('F j Y', strtotime($dateTo)) . '.xlsx';
        $filePath = storage_path('app/' . $filename);

        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/AccountingReportPartials/DepositedCheckResult', [
            'downloadExcel' => $downloadExcel,
        ]);
    }

}
