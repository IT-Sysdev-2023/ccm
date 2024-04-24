<?php
namespace App\Helper\Excel;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelWriter
{

    protected Spreadsheet $spreadsheet;
    public function __construct()
    {

        $this->spreadsheet = new Spreadsheet();

    }

    function executionTime() : void {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
    }

    function getActiveSheetExcel(): Worksheet
    {
        return $this->spreadsheet->getActiveSheet();
    }
    function getStyleGetFontSetBold($style): Font
    {
        return $this->getActiveSheetExcel()->getStyle($style)->getFont()->setBold(true);

    }
    function getCellSetValue($cell, $value): Cell
    {
        return $this->getActiveSheetExcel()->getCell($cell)->setValue($value);
    }

    function setHorizontalAlignment($excel): Alignment  {
        return $this->getActiveSheetExcel()->getStyle($excel)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    function setCellValueSheet($col, $title): Worksheet  {
        return $this->getActiveSheetExcel()->setCellValue($col, $title);
    }

    function initializedBorder(): array
    {
        return [
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
    }
    function initializedBorderFontBoldNone(): array
    {
        return [
            'font' => [
                'color' => ['rgb' => '454545'], // Specify the color in RGB format
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
    }
    function singleBorderWithBgColor(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Border color (black)
                ],
            ],
        ];
    }
}
