<?php

namespace App\Services;

use App\Helper\Excel\ExcelWriter;
use App\Models\Checks;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\LazyCollection;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Events\GenerateBounceCheckEvents;
use Illuminate\Support\Facades\Auth;

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
                "REPLACEMENT DATE",
                "CASH AMOUNT",
                "PENALTY",
                "AR/DS",
                "REASON",
                "TREASURY USER",

            ],
        );
        $this->generateReportHeaderReDeposit = array(
            [
                "REPLACEMENT DATE",
                "PENALTY",
                "AR/DS",
                "REASON",
                "TREASURY USER",

            ],
        );

        $this->generateReportHeaderCheckRep = array(
            [
                "REPLACEMENT DATE",
                "REPLACEMENT CHECK AMOUNT",
                "REPLACEMENT CHECK NO.",
                "PENALTY",
                "AR/DS",
                "REASON",
                "TREASURY USER",

            ],
        );
        $this->generateReportHeaderCheckDetails = array(
            [
                "NO.",
                "CHECK RECEIVED",
                "CHECK DATE",
                "ACCOUNT NO",
                "ACCOUNT NAME",
                "BANK",
                "CUSTOMER",
                "APPROVING OFFICER",
                "CHECK CLASS",
                "CHECK CATEGORY",
                "CHECK FROM",
                "CHECK NUMBER",
                "AMOUNT",

            ],
        );
        $this->generateReportHeaderCheckCash = array(
            [
                "REPLACEMENT DATE",
                "REPLACEMENT CHECK AMOUNT",
                "REPLACEMENT CHECK NO.",
                "REPLACEMENT CASH AMOUNT.",
                "PENALTY",
                "AR/DS",
                "REASON",
                "USER",

            ],
        );
        $this->generateReportHeaderPartial = array(
            [
                "DATE",
                "BOUNCE AMOUNT",
                "CASH PAID.",
                "CHECK PAID",
                "BALANCE",
                "PENALTY",
                "CHECK NUMBER",
                "AR/DS",
                "TREASURY",

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
        // dd($bounceStatus);

        $header = $this->generateUserHeader;
        $replacementHeader = $this->generateReportHeader;

        $bunitRow = 7;
        $headerRow = 2;
        $excelRow = 9;

        if ($bounceStatus == 0 || $bounceStatus == null) {
            $bStatusHeader = 'ALL BOUNCE CHECKS';
        } elseif ($bounceStatus == "1") {
            $bStatusHeader = "PENDING CHECKS";
        } else {
            $bStatusHeader = "SETTLED CHECKS";
        }

        if ($bunitCode == "2") {
            $bunitHeader = 'ASC-MAIN';
        } elseif ($bunitCode == "4") {
            $bunitHeader = "ISLAND CITY MALL";
        } else {
            $bunitHeader = "PLAZA MARCELA";
        }

        if ($dateRange[0] == null) {
            $dateGenerate = 'This day ' . today()->toFormattedDateString();
        } else {
            $dateGenerate = 'From ' . Date::parse($dateRange[0])->toFormattedDateString() . ' To ' . Date::parse($dateRange[1])->toFormattedDateString();
        }

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $bStatusHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':O' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':O' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':O' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $dateGenerate);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':O' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':O' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':O' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $bunitHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':O' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':O' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':O' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('B' . $bunitRow, "SETTLED CHECKS");
        $this->getActiveSheetExcel()->getColumnDimension('B')->setWidth(20);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('B' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $cell = 'B' . $bunitRow;
        $style = $this->getActiveSheetExcel()->getStyle($cell);
        $fill = $style->getFill();
        $fill->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $fill->getStartColor()->setARGB('68D2E8');

        $this->getActiveSheetExcel()->setCellValue('D' . $bunitRow, "PENDING CHECKS");
        $this->getActiveSheetExcel()->getColumnDimension('D')->setWidth(20);
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getFont()->setBold(true);
        $this->getActiveSheetExcel()->getStyle('D' . $bunitRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $cell = 'D' . $bunitRow;
        $style = $this->getActiveSheetExcel()->getStyle($cell);
        $fill = $style->getFill();
        $fill->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $fill->getStartColor()->setARGB('F78CA2');

        $bunitRow++;

        $num = 1;
        $startCount = 1;

        // dd($this->record->toArray());

        $itemCount = count($this->record);

        // dd($itemCount);

        $this->record->each(function ($item) use (&$excelRow, $header, &$num, &$startCount, &$replacementHeader, &$bunitCode, $itemCount) {

            if ($item->status != 'SETTLED CHECK') {
                $status = 'PENDING';
            } else {
                $status = $item->status;
            }

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
                $status,
            ];

            $this->getActiveSheetExcel()->fromArray($header, null, 'A' . $excelRow);

            foreach (range('A', 'O') as $col) {
                $cell = $col . $excelRow;

                if ($item->status == 'SETTLED CHECK') {
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '68D2E8'], // Yellow color, you can change it as needed
                    ];
                } else {
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F78CA2'], // Yellow color, you can change it as needed
                    ];
                }

                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);

            }

            $excelRow++;

            $this->spreadsheet->getActiveSheet()->fromArray($reportDataCollection, null, "A$excelRow");

            foreach (range('A', 'O') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            foreach (range('A', 'O') as $col) {
                $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
            }

            $excelRow++;

            if ($item->status != '') {

                $modeData = NewCheckReplacement::where('new_check_replacement.bounce_id', '=', $item->id)
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->first();

                $modeDataReplacement = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
                    ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                    ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->where('checks.businessunit_id', $bunitCode)
                    ->where('new_check_replacement.bounce_id', '=', $item->id)
                    ->select('*', 'new_check_replacement.date_time')
                    ->first();

                // dd($modeDataReplacement->toArray());

                $dataModeCollection[] = [
                    'date_time' => $modeDataReplacement->date_time,
                    'cash' => $modeDataReplacement->cash,
                    'penalty' => $modeDataReplacement->penalty,
                    'ar_ds' => $modeDataReplacement->date_time,
                    'reason' => $modeDataReplacement->reason,
                    'mode' => $modeDataReplacement->name,
                ];
                $fillColor = [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F78CA2'], // Yellow color, you can change it as needed
                ];

                if ($modeData->mode == 'CASH') {
                    $this->getActiveSheetExcel()->setCellValue('F' . $excelRow, 'REPLACEMENT DETAILS - CASH');
                    $this->getActiveSheetExcel()->mergeCells('F' . $excelRow . ':K' . $excelRow);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':K' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':K' . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':K' . $excelRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFCCCCCC');
                } elseif ($modeData->mode == 'RE-DEPOSIT') {
                    $this->getActiveSheetExcel()->setCellValue('F' . $excelRow, 'REPLACEMENT DETAILS - RE-DEPOSIT');
                    $this->getActiveSheetExcel()->mergeCells('F' . $excelRow . ':J' . $excelRow);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':J' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':J' . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':J' . $excelRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFCCCCCC');
                } elseif ($modeData->mode == 'CHECK') {
                    $this->getActiveSheetExcel()->setCellValue('F' . $excelRow, 'REPLACEMENT DETAILS - CHECK');
                    $this->getActiveSheetExcel()->mergeCells('F' . $excelRow . ':L' . $excelRow);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':L' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':L' . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':L' . $excelRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFCCCCCC');
                } elseif ($modeData->mode == 'CHECK & CASH') {
                    $this->getActiveSheetExcel()->setCellValue('F' . $excelRow, 'REPLACEMENT DETAILS - CHECK & CASH');
                    $this->getActiveSheetExcel()->mergeCells('F' . $excelRow . ':M' . $excelRow);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':M' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':M' . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle('F' . $excelRow . ':M' . $excelRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFCCCCCC');
                } elseif ($modeData->mode == 'PARTIAL') {
                    $this->getActiveSheetExcel()->setCellValue('D' . $excelRow, 'REPLACEMENT DETAILS - PARTIAL');
                    $this->getActiveSheetExcel()->mergeCells('D' . $excelRow . ':L' . $excelRow);
                    $this->getActiveSheetExcel()->getStyle('D' . $excelRow . ':L' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle('D' . $excelRow . ':L' . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle('D' . $excelRow . ':L' . $excelRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFCCCCCC');
                }

                $excelRow++;

                if ($modeData->mode == 'CASH') {

                    $this->getActiveSheetExcel()->fromArray($replacementHeader, null, 'F' . $excelRow);

                    foreach (range('F', 'K') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    $excelRow++;
                    $this->spreadsheet->getActiveSheet()->fromArray($dataModeCollection, null, "F$excelRow");
                    foreach (range('F', 'K') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow += 2;

                } elseif ($modeData->mode == 'RE-DEPOSIT') {

                    $reDepositModeCollection[] = [
                        'date_time' => $modeDataReplacement->date_time,
                        'penalty' => $modeDataReplacement->penalty,
                        'ar_ds' => $modeDataReplacement->date_time,
                        'reason' => $modeDataReplacement->reason,
                        'mode' => $modeDataReplacement->name,
                    ];

                    $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderReDeposit, null, 'F' . $excelRow);

                    foreach (range('F', 'J') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    $excelRow++;
                    $this->spreadsheet->getActiveSheet()->fromArray($reDepositModeCollection, null, "F$excelRow");
                    foreach (range('F', 'J') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow += 2;
                } elseif ($modeData->mode == 'CHECK') {
                    $checkno = '';
                    $ar_ds = '';

                    $repCheckNo = Checks::where('checks_id', '=', $modeDataReplacement->rep_check_id)->first();

                    if ($repCheckNo == null) {

                    } else {
                        $checkno = $repCheckNo->check_no;
                    }

                    $repArDs = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
                        ->where('new_ds_checks.checks_id', '=', $modeDataReplacement->rep_check_id)
                        ->first();

                    if ($repArDs == null) {

                    } else {
                        $ar_ds = $repArDs->ds_no;
                    }

                    $checkModeCollection[] = [
                        $modeDataReplacement->date_time,
                        $modeDataReplacement->check_amount_paid,
                        $checkno,
                        $modeDataReplacement->penalty,
                        $ar_ds,
                        $modeDataReplacement->reason,
                        $modeDataReplacement->name,
                    ];

                    $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderCheckRep, null, 'F' . $excelRow);

                    foreach (range('F', 'L') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;

                    $this->spreadsheet->getActiveSheet()->fromArray($checkModeCollection, null, "F$excelRow");
                    foreach (range('F', 'L') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;

                    $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderCheckDetails, null, 'A' . $excelRow);

                    foreach (range('A', 'M') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;

                    $replacementCheckData = Checks::where('checks_id', $repCheckNo->checks_id)
                        ->join('customers', 'customers.customer_id', '=', 'checks.customer_id')
                        ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
                        ->join('department', 'department.department_id', '=', 'checks.department_from')
                        ->first();

                    $repCheckCollection[] = [
                        '*',
                        $replacementCheckData->check_received,
                        $replacementCheckData->check_date,
                        $replacementCheckData->account_no,
                        $replacementCheckData->account_name,
                        $replacementCheckData->bankbranchname,
                        $replacementCheckData->fullname,
                        $replacementCheckData->approving_officer,
                        $replacementCheckData->check_class,
                        $replacementCheckData->check_category,
                        $replacementCheckData->department,
                        $replacementCheckData->check_no,
                        $replacementCheckData->check_amount,
                    ];

                    $this->spreadsheet->getActiveSheet()->fromArray($repCheckCollection, null, "A$excelRow");

                    foreach (range('A', 'M') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    $excelRow += 2;
                } elseif ($modeData->mode == 'CHECK & CASH') {

                    $checkno = '';
                    $ar_ds = '';

                    $repCheckNo = Checks::where('checks_id', '=', $modeDataReplacement->rep_check_id)->first();

                    if ($repCheckNo == null) {

                    } else {
                        $checkno = $repCheckNo->check_no;
                    }

                    $repArDs = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
                        ->where('new_ds_checks.checks_id', '=', $modeDataReplacement->rep_check_id)
                        ->first();

                    if ($repArDs == null) {

                    } else {
                        $ar_ds = $repArDs->ds_no;
                    }

                    $checkModeCollection[] = [
                        $modeDataReplacement->date_time,
                        $modeDataReplacement->check_amount_paid,
                        $checkno,
                        $modeDataReplacement->cash,
                        $modeDataReplacement->penalty,
                        $ar_ds,
                        $modeDataReplacement->reason,
                        $modeDataReplacement->name,
                    ];

                    $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderCheckCash, null, 'F' . $excelRow);

                    foreach (range('F', 'M') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;
                    $this->spreadsheet->getActiveSheet()->fromArray($checkModeCollection, null, "F$excelRow");

                    foreach (range('F', 'M') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;

                    $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderCheckDetails, null, 'A' . $excelRow);

                    foreach (range('A', 'M') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;

                    $replacementCheckData = Checks::where('checks_id', $repCheckNo->checks_id)
                        ->join('customers', 'customers.customer_id', '=', 'checks.customer_id')
                        ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
                        ->join('department', 'department.department_id', '=', 'checks.department_from')
                        ->first();

                    $repCheckCollection[] = [
                        '*',
                        $replacementCheckData->check_received,
                        $replacementCheckData->check_date,
                        $replacementCheckData->account_no,
                        $replacementCheckData->account_name,
                        $replacementCheckData->bankbranchname,
                        $replacementCheckData->fullname,
                        $replacementCheckData->approving_officer,
                        $replacementCheckData->check_class,
                        $replacementCheckData->check_category,
                        $replacementCheckData->department,
                        $replacementCheckData->check_no,
                        $replacementCheckData->check_amount,
                    ];

                    $this->spreadsheet->getActiveSheet()->fromArray($repCheckCollection, null, "A$excelRow");

                    foreach (range('A', 'M') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    $excelRow += 2;

                } elseif ($modeData->mode == 'PARTIAL') {

                    // $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    // $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderPartial, null, 'D' . $excelRow);

                    foreach (range('D', 'L') as $col) {
                        $cell = $col . $excelRow;
                        $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                        $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    $excelRow++;

                    $partialData = NewCheckReplacement::join('users', 'users.id', '=', 'new_check_replacement.user')
                        ->where('new_check_replacement.checks_id', $item->checks_id)
                        ->where('new_check_replacement.mode', 'PARTIAL')
                        ->orderBy('date_time', 'ASC')
                        ->cursor();

                    // dd(  $partialData->toArray() );

                    $cash = 0;
                    $count = 0;

                    $amount = 0;
                    $balance1 = 0;
                    $balance = 0;
                    $partialData->each(function ($item) use (&$count, &$cash, &$balance1, &$balance, &$amount, &$excelRow) {
                        // $balance = 0;
                        if ($count == 0) {

                            $amount = $item->check_amount; //50,000
                            // dump($amount);

                            $balance1 = $item->check_amount - $item->cash; //10,000 = 40000
                            // dump($balance1);
                            if ($item->check_amount_paid != 0) {
                                $balance1 = $item->check_amount - $item->check_amount_paid;

                            }
                            // dd(1);
                        } else {

                            $amount = $balance;

                            $balance1 = $balance1 - $item->cash;
                            // dump($amount);
                            if ($item->check_amount_paid != 0) {
                                $balance1 = $balance1 - $item->check_amount_paid;
                            }
                            // dd(2);
                        }

                        $balance = $amount - $cash;

                        $partailCheckNo = '';

                        if ($item->check_amount_paid != 0) {
                            $checkNoData = Checks::where('checks_id', $item->rep_check_id)
                                ->first();
                            $partailCheckNo = $checkNoData->check_no . ' [Check Amount: ( ' . $checkNoData->check_amount . ') ]';
                        }

                        $partialDsNo = '';

                        if ($item->check_amount_paid != 0) {
                            $dsData = Checks::where('checks_id', $item->rep_check_id)
                                ->first();
                            if ($dsData != null) {
                                $partialDsNo = $dsData->ds_no;
                            }
                        } else {
                            $partialDsNo = $item->ar_ds;
                        }

                        $partialModeCollection[] = [
                            $item->date_time,
                            number_format($balance),
                            number_format($item->cash),
                            number_format($item->check_amount_paid),
                            number_format($balance1),
                            number_format($item->penalty),
                            $partailCheckNo,
                            $partialDsNo,
                            $item->name,

                        ];
                        // dump($partialModeCollection);

                        $this->spreadsheet->getActiveSheet()->fromArray($partialModeCollection, null, "D$excelRow");

                        foreach (range('D', 'L') as $col) {
                            $cell = $col . $excelRow;
                            $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                            $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        }

                        $cash = $item->cash;

                        if ($item->check_amount_paid != 0) {
                            $cash = $item->check_amount_paid;
                        }

                        $count++;
                        $excelRow++;

                    });
                    // dd(3);

                    $excelRow += 2;
                }
            }

            GenerateBounceCheckEvents::dispatch('Generating Bounce Checks ', $startCount++, $itemCount, Auth::user());

        });

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        $filename = 'samplebounce'. '.xlsx';
        $filePath = storage_path('app/' . $filename);

        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/ReportPartials/ResultBouncedReports', [
            'downloadExcel' => $downloadExcel,
        ]);

    }
}
