<?php

namespace App\Services;

use App\Helper\Excel\ExcelWriter;
use Illuminate\Support\LazyCollection;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\NewCheckReplacement;
class ReportBounceCheckService extends ExcelWriter
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
            "BOUNCE DATE",
            "CHECK RECEIVE",
            "CHECK DATE",
            "CLASS",
            "CATEGORY",
            "CHECK FROM",
            "BANK",
            "ACCOUNT NUMBER",
            "ACCOUNT NAME",
            "CUSTOMER",
            "APPROVING OFFICER",
            "CHECK NUMBER",
            "AMOUNT",
            "STATUS",
        ];

        $this->generateReportHeader = array(
            [
                "NO",
                "REPLACEMENT DATE",
                "CASH AMOUNT",
                "PENALTY",
                "AR/DS",
                "REASON",
                "TREASURY USER",

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

    public function writeResult($bunitCode, $bounceStatus, $dateRange)
    {

        $header = $this->generateUserHeader;
        $replacementHeader = $this->generateReportHeader;

        $bunitRow = 2;
        $excelRow = 5;

        $this->getActiveSheetExcel()->setCellValue('B' . $bunitRow, "Settled Checks");
        $this->getActiveSheetExcel()->getColumnDimension('B')->setWidth(20);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->getColor()->setRGB('ffffff');

        $cell = 'B' . $bunitRow;
        $style = $this->getActiveSheetExcel()->getStyle($cell);
        $fill = $style->getFill();
        $fill->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $fill->getStartColor()->setARGB('1679AB');

        $this->getActiveSheetExcel()->setCellValue('D' . $bunitRow, "Pending Checks");
        $this->getActiveSheetExcel()->getColumnDimension('D')->setWidth(20);
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getFont()->getColor()->setRGB('ffffff');

        $cell = 'D' . $bunitRow;
        $style = $this->getActiveSheetExcel()->getStyle($cell);
        $fill = $style->getFill();
        $fill->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $fill->getStartColor()->setARGB('f44336');

        $num = 1;

        // dd($this->record->toArray());

        $this->record->each(function ($item) use (&$excelRow, $header, &$num, &$replacementHeader) {
            $reportDataCollection[] = [
                $num++,
                $item->date_time,
                $item->check_received,
                $item->check_date,
                $item->check_class,
                $item->check_category,
                $item->department,
                $item->bankbranchname,
                $item->account_no,
                $item->account_name,
                $item->fullname,
                $item->approving_officer,
                $item->check_no,
                $item->check_amount,
                $item->status,
            ];

            $this->getActiveSheetExcel()->fromArray($header, null, 'A' . $excelRow);

            foreach (range('A', 'O') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $excelRow++;

            $this->spreadsheet->getActiveSheet()->fromArray($reportDataCollection, null, "A$excelRow");

            foreach (range('A', 'O') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $excelRow += 3;

            if($item->status != ''){
                // dd(1);
                $modeData = NewCheckReplacement::where('new_check_replacement.bounce_id', '=', $item->id)
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->first();

                if($modeData->mode == 'CASH'){
                    $this->getActiveSheetExcel()->fromArray($replacementHeader, null, 'A' . $excelRow);
                    $excelRow++;
                }
            }

        });

        foreach (range('A', 'O') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        $filename = 'samplebounce';
        $filePath = storage_path('app/' . $filename);

        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/ReportPartials/ResultBouncedReports', [
            'downloadExcel' => $downloadExcel,
        ]);

    }
}
