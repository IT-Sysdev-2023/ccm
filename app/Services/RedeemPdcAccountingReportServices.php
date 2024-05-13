<?php

namespace App\Services;

use App\Helper\Excel\ExcelWriter;
use App\Models\Checks;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RedeemPdcAccountingReportServices extends ExcelWriter
{

    protected $record;
    protected $border;
    protected $borderFBN;
    protected $singleBorderWithBgColor;
    public function __construct()
    {
        parent::__construct();

        $this->generateUserHeader = [
            "NO",
            "REPLACEMENT DATE",
            "CHECK RECEIVED",
            "CHECK DATE",
            "ACCOUNT NUMBER",
            "ACCOUNT NAME",
            "BANK",
            "CUSTOMER",
            "APPROVING OFFICER",
            "CHECK CLASS",
            "CHECK CATEGORY",
            "CHECK FROM",
            "CHECK NUMBER",
            "AMOUNT",
        ];

        $this->border = $this->initializedBorder();
        $this->borderFBN = $this->initializedBorderFontBoldNone();
        $this->singleBorderWithBgColor = $this->singleBorderWithBgColor();

    }

    public function record($data)
    {
        $this->record = $data;

        return $this;
    }

    public function writeResult($dateRange, $bunit)
    {

        $header = $this->generateUserHeader;
        // $replacementHeader = $this->generateReportHeader;

        $bStatusHeader = "REPLACED CHEQUES";
        $bunitHeader = $bunit[0]->bname;

        $bunitRow = 7;
        $headerRow = 2;
        $excelRow = 9;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $bStatusHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':N' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':N' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':N' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        // $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $dateGenerate);
        // $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':N' . $headerRow);
        // $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':N' . $headerRow);
        // $font = $style->getFont();
        // $font->setBold(true);
        // $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':N' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $bunitHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':N' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':N' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':N' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->record->each(function ($item) use (&$excelRow, &$header) {

            $replacedCheck = Checks::where('checks_id', $item->checks_id)
                ->join('customers', 'customers.customer_id', '=', 'checks.customer_id')
                ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->first();

            $this->getActiveSheetExcel()->fromArray($header, null, 'A' . $excelRow);
            foreach (range('A', 'N') as $col) {
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

            $dataCollection[] = [
                $item->date_time,
                $replacedCheck->check_received,
                $replacedCheck->check_date,
                $replacedCheck->account_name,
                $replacedCheck->bankbranchname,
                $replacedCheck->fullname,
                $replacedCheck->approving_officer,
                $replacedCheck->check_class,
                $replacedCheck->check_category,
                $replacedCheck->department,
                $replacedCheck->check_no,
                $replacedCheck->check_amount,

            ];

            $this->spreadsheet->getActiveSheet()->fromArray($dataCollection, null, "A$excelRow");

            $mode = NewCheckReplacement::where('new_check_replacement.id', '=', $item->id)
                ->join('users', 'users.id', '=', 'new_check_replacement.user')
                ->first();
        });
        foreach (range('A', 'N') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        // $filename = $bunitHeader . ' Report from ' . date('F j Y', strtotime($dateFrom)) . ' to ' . date('F j Y', strtotime($dateTo)) . '.xlsx';
        $filename = 'Sample' . '.xlsx';
        $filePath = storage_path('app/' . $filename);

        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/AccountingReportPartials/RedeemPdcCheckReportResult', [
            'downloadExcel' => $downloadExcel,
        ]);

    }

}
