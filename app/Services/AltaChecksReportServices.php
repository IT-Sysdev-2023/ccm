<?php

namespace App\Services;

use App\Helper\Excel\ExcelWriter;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Inertia\Inertia;
use Illuminate\Support\Facades\Date;
use App\Events\GeneratesFunctionEvents;
use Illuminate\Support\Facades\Auth;

class AltaChecksReportServices extends ExcelWriter
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
            "CHECK NUMBER",
            "CHECK RECEIVED",
            "CHECK DATE",
            "BANK",
            "CURRENCY",
            "AMOUNT",
            "DS NO",
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

    public function writeResult($dateMonth)
    {
        $excelRow = 5;
        $headerRow = 2;

        $dateMonthHeader = ' On ' . Date::parse($dateMonth)->format('F Y');

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, 'CHECKS FROM ALTA-CITA');
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':H' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':H' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':H' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $dateMonthHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':H' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':H' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':H' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->fromArray($this->generateUserHeader, null, 'A' . $excelRow);

        foreach (range('A', 'H') as $col) {
            $cell = $col . $excelRow;

            $fillColor = [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '68D2E8'], // Yellow color, you can change it as needed
            ];

            $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
            $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
            $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);

        }
        $excelRow++;
        $num = 1;
        $startCount = 0;
        $itemCount = count($this->record);

        $this->record->each(function ($item) use (&$excelRow, &$num, &$startCount, $itemCount) {

            // dd($item);
            $altaChecksDataCollection[] = [
                $num++,
                $item->check_num,
                $item->check_recieved,
                $item->check_date,
                $item->bank,
                $item->currency,
                $item->amount,
                $item->official_ds,
            ];

            $this->spreadsheet->getActiveSheet()->fromArray($altaChecksDataCollection, null, "A$excelRow");
            foreach (range('A', 'H') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $excelRow++;

            GeneratesFunctionEvents::dispatch('Generating Alta-Cita Checks Reports...', $startCount++, $itemCount, Auth::user());;
        });

        foreach (range('A', 'H') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }



        $filename = 'Alta-cita Report' . $dateMonthHeader . '.xlsx';

        $filePath = storage_path('app/' . $filename);

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/ReportPartials/ChecksAltaReportResult', [
            'downloadExcel' => $downloadExcel,
        ]);


    }

}
