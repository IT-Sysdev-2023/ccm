<?php
namespace App\Helper\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelWriter
{

    protected Spreadsheet $spreadsheet;
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    function getActiveSheetExcel()
    {
        return $this->spreadsheet->getActiveSheet();
    }
    function getStyleGetFontSetBold($style)
    {
        return $this->getActiveSheetExcel()->getStyle($style)->getFont()->setBold(true);

    }
    function getCellSetValue($cell, $value)
    {
        return $this->getActiveSheetExcel()->getCell($cell)->setValue($value);
    }

    function setHorizontalAlignment($excel)  {
        return $this->getActiveSheetExcel()->getStyle($excel)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    function setCellValueSheet($col, $title)  {
        return $this->getActiveSheetExcel()->setCellValue($col, $title);
    }

    function initializedBorder()
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
}