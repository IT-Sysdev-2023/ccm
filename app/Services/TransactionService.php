<?php
namespace App\Services;

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

    function record(object $data): self
    {
        $this->record = $data;

        return $this;
    }
    public function headername()
    {
        return $this;
    }


    public function writeResult(string $status, array $dateRange)
    {



        $headerTitle = '';
        $reportData = [];

        $generate_date = today()->toFormattedDateString();
        $countTable = 1;
        $grandTotal = 0;
        $excel_row = 5;


        if ($status == '1') {
            $reportData = $this->record;
            $headerTitle = 'Dated Check Report';
        } else {
            $reportData = $this->record;
            $headerTitle = 'Post Dated Check Report';

        }


        $spreadsheet = new Spreadsheet();

        if ($status == '1') {
            $headerRow = array(
                "NO",
                "CUSTOMER NAME",
                "CHECK NO",
                "CHECK DATE",
                "AMOUNT",
                "ACCOUNT NO",
                "ACCOUNT NAME",
                "BANK NAME",
            );
        } else {
            $headerRow = array(
                "NO",
                "CUSTOMER NAME",
                "CHECK NO",
                "CHECK DATE",
                "AMOUNT",
                "ACCOUNT NO",
                "ACCOUNT NAME",
                "BANK NAME",
                "STATUS",
            );
        }

        $spreadsheet->getActiveSheet()->getCell('E1')->setValue('Status Type : ' . ' ' . $headerTitle);
        $spreadsheet->getActiveSheet()->getCell('E2')->setValue('Date : ' . ' ' . $generate_date);

        $spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);

        $excel_row = 4;

        foreach ($reportData as $department => $values) {


            // Set department name
            $spreadsheet->getActiveSheet()->setCellValue('A' . $excel_row, $department);


            // Merge cells for the department name
            $spreadsheet->getActiveSheet()->mergeCells('A' . $excel_row . ':I' . $excel_row);

            // Center align the department name
            $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A' . $excel_row)->getFont()->setBold(true);
            // Set header row
            $headerRowIndex = $excel_row + 1;
            $spreadsheet->getActiveSheet()->fromArray($headerRow, null, 'A' . $headerRowIndex);
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

            foreach ($values as $value) {
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
            }

            $spreadsheet->getActiveSheet()->setCellValue('E' . ($excel_row + count($values) + 1), 'Subtotal:');
            $spreadsheet->getActiveSheet()->setCellValue('F' . ($excel_row + count($values) + 1), number_format($subtotal, 2));

            $spreadsheet->getActiveSheet()->getStyle('E' . ($excel_row + count($values) + 1))->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('F' . ($excel_row + count($values) + 1))->getFont()->setBold(true);

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
            $spreadsheet->getActiveSheet()->fromArray($reportCollection, null, "A$excel_row");



            // Set borders for the data
            $highestRow = $excel_row + count($values); // Determine the last row of data for this department
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

            $excel_row += count($values) + 5; // Increment row number for the next department
        }


        $spreadsheet->getActiveSheet()->setCellValue('E' . ($excel_row), 'Grand Total:');
        $spreadsheet->getActiveSheet()->setCellValue('F' . ($excel_row), number_format($grandTotal, 2));
        $spreadsheet->getActiveSheet()->getStyle('E' . ($excel_row))->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('F' . ($excel_row))->getFont()->setBold(true);





        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = $headerTitle . ' on ' . now()->format('M, d Y') . '.xlsx';


        return response()->download($tempFilePath, $filename);

    }

    public function writeResultDuePdc(array $dateRange, $businessUnit)
    {

        $dueReportData = $this->record;


        $spreadsheet = new Spreadsheet();


        // dd($dateRange);
        if (!empty($dateRange[0]) && !empty($dateRange[1])) {
            $spreadsheet->getActiveSheet()->getCell('A2')->setValue('From : ' . Date::parse($dateRange[0])->toFormattedDateString() . ' To: ' . Date::parse($dateRange[1])->toFormattedDateString());
        } else {
            $spreadsheet->getActiveSheet()->getCell('A2')->setValue('From : ' . today()->toFormattedDateString());
        }

        $spreadsheet->getActiveSheet()->getCell('A1')->setValue('Status Type : ' . ' ' . $businessUnit->bname);

        $spreadsheet->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);


        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = $businessUnit->bname . ' on ' . now()->format('M, d Y') . '.xlsx';


        return response()->download($tempFilePath, $filename);
    }




}
