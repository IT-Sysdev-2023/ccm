<?php

namespace App\Services;

use App\Helper\Excel\ExcelWriter;
use App\Models\Checks;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use App\Events\RedeemCheckReportEvents;
class RedeemPdcReportServices extends ExcelWriter
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
            "CHECK FROM",
            "CHECK NUMBER",
            "AMOUNT",
        ];

        $this->generateReportHeader = array(
            [
                "REPLACEMENT DATE",
                "CASH AMOUNT",
                "PENALTY",
                "AR/DS",
                "REASON",
                "USER",

            ],
        );
        $this->generateCheckReportHeader = array(
            [
                "REPLACEMENT DATE",
                "REPLACEMENT CHECK AMOUNT",
                "REPLACEMENT CHECK NO.",
                "PENALTY",
                "AR/DS",
                "REASON",
                "USER",

            ],
        );
        $this->generateCheckCashReportHeader = array(
            [
                "REPLACEMENT DATE",
                "REPLACEMENT CHECK AMOUNT",
                "REPLACEMENT CHECK NO.",
                "REPLACEMENT CASH AMOUNT",
                "PENALTY",
                "AR/DS",
                "REASON",
                "USER",

            ],
        );

        $this->generateReportHeaderCheckDetails = array(
            [
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

        $this->generatePartialReportHeader = array(
            [
                "DATE",
                "PENDING AMOUNT",
                "CASH PAID",
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

    public function record($data)
    {
        $this->record = $data;

        return $this;
    }

    public function writeResult($dateRange, $bunit, $url)
    {
        // dd($bunit->bname);

        $header = $this->generateUserHeader;
        // $replacementHeader = $this->generateReportHeader;

        $bStatusHeader = "REPLACED CHEQUES";
        $bunitHeader = $bunit->bname;
        $dateGenerate = 'From ' .  date('F j Y', strtotime($dateRange[0])) . ' to '.  date('F j Y', strtotime($dateRange[1]));

        $bunitRow = 7;
        $headerRow = 2;
        $excelRow = 9;
        $num = 1;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $bStatusHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':M' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':M' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':M' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $dateGenerate);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':M' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':M' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':M' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $this->getActiveSheetExcel()->setCellValue('A' . $headerRow, $bunitHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $headerRow . ':M' . $headerRow);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':M' . $headerRow);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $headerRow . ':M' . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $headerRow++;

        $progressCount  =  0;

        $itemCount = count($this->record);
;
        $this->record->each(function ($item) use (&$excelRow, &$num, &$header, &$bunit, &$progressCount, $itemCount) {

            $replacedCheck = Checks::where('checks_id', $item->checks_id)
                ->join('customers', 'customers.customer_id', '=', 'checks.customer_id')
                ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
                ->join('department', 'department.department_id', '=', 'checks.department_from')
                ->first();

            // dd($replacedCheck->check_no);

            $this->getActiveSheetExcel()->fromArray($header, null, 'A' . $excelRow);

            foreach (range('A', 'M') as $col) {
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

            foreach (range('E', 'J') as $col) {
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
                $num++,
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

            // dd($dataCollection);

            $this->spreadsheet->getActiveSheet()->fromArray($dataCollection, null, "A$excelRow");

            foreach (range('A', 'M') as $col) {
                $cell = $col . $excelRow;
                $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
            }

            $excelRow++;

            if ($item->mode == 'CASH') {

                $queryReplaced = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
                    ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                    ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->where('checks.businessunit_id', '=', $bunit->businessunit_id)
                    ->where('new_check_replacement.id', $item->id)
                    ->select('*', 'new_check_replacement.date_time')->first();

                $this->getActiveSheetExcel()->setCellValue('E' . $excelRow, 'REPLACEMENT MODE: CASH');
                $this->getActiveSheetExcel()->mergeCells('E' . $excelRow . ':J' . $excelRow);
                $style = $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow);
                $font = $style->getFont();
                $font->setBold(true);
                $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'CAF4FF'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;

                $this->getActiveSheetExcel()->fromArray($this->generateReportHeader, null, 'E' . $excelRow);
                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow++;

                $dataCashCollection[] = [
                    $queryReplaced->date_time ?? null,
                    $queryReplaced->cash ?? null,
                    $queryReplaced->penalty ?? null,
                    $queryReplaced->ar_ds ?? null,
                    $queryReplaced->reason ?? null,
                    $queryReplaced->name ?? null,
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($dataCashCollection, null, "E$excelRow");

                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow += 2;

            } elseif ($item->mode == 'RE-DEPOSIT') {
                // dd(1);
                $queryReplaced = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
                    ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                    ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->where('checks.businessunit_id', '=', $bunit->businessunit_id)
                    ->where('new_check_replacement.id', $item->id)
                    ->select('*', 'new_check_replacement.date_time')->first();

                $this->getActiveSheetExcel()->setCellValue('E' . $excelRow, 'REPLACEMENT MODE: RE-DEPOSIT');
                $this->getActiveSheetExcel()->mergeCells('E' . $excelRow . ':J' . $excelRow);
                $style = $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow);
                $font = $style->getFont();
                $font->setBold(true);
                $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'CAF4FF'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;

                $this->getActiveSheetExcel()->fromArray($this->generateReportHeader, null, 'E' . $excelRow);
                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow++;

                $dataDepositCollection[] = [
                    $queryReplaced->date_time,
                    $queryReplaced->cash,
                    $queryReplaced->penalty,
                    $queryReplaced->ar_ds,
                    $queryReplaced->reason,
                    $queryReplaced->name,
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($dataDepositCollection, null, "E$excelRow");

                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow += 2;

            } elseif ($item->mode == 'CHECK') {
                $queryReplaced = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
                    ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                    ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->where('checks.businessunit_id', '=', $bunit->businessunit_id)
                    ->where('new_check_replacement.id', $item->id)
                    ->select('*', 'new_check_replacement.date_time')->first();

                    // dd($queryReplaced);

                $this->getActiveSheetExcel()->setCellValue('E' . $excelRow, 'REPLACEMENT MODE: CHECK');
                $this->getActiveSheetExcel()->mergeCells('E' . $excelRow . ':J' . $excelRow);
                $style = $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow);
                $font = $style->getFont();
                $font->setBold(true);
                $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'CAF4FF'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;

                $this->getActiveSheetExcel()->fromArray($this->generateCheckReportHeader, null, 'E' . $excelRow);
                foreach (range('E', 'K') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;

                $checkNumber = '';
                $arDs = '';

                // dd($queryReplaced->rep_check_id ?? null);

                $repCheckNumber = Checks::where('checks_id', '=', $queryReplaced?->rep_check_id)->first();

                $repArDs = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
                    ->where('new_ds_checks.checks_id', '=', $queryReplaced?->rep_check_id)
                    ->first();

                if ($repCheckNumber == null) {
                    // nothing
                } else {
                    $checkNumber = $repCheckNumber->check_no;
                }
                if ($repArDs == null) {
                    //nothing
                } else {
                    $arDs = $repArDs->ds_no;
                }

                $dataCheckCollection[] = [
                    $queryReplaced->date_time ?? null,
                    $queryReplaced->check_amount_paid ?? null,
                    $checkNumber ?? null,
                    $queryReplaced->penalty ?? null,
                    $arDs ?? null,
                    $queryReplaced->reason ?? null,
                    $item->name ?? null,
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($dataCheckCollection, null, "E$excelRow");

                foreach (range('E', 'K') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow += 2;
            } elseif ($item->mode == 'CHECK & CASH') {
                $queryReplaced = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
                    ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                    ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
                    ->join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->where('checks.businessunit_id', '=', $bunit->businessunit_id)
                    ->where('new_check_replacement.id', $item->id)
                    ->select('*', 'new_check_replacement.date_time')->first();

                $this->getActiveSheetExcel()->setCellValue('E' . $excelRow, 'REPLACEMENT MODE: CHECK AND CASH');
                $this->getActiveSheetExcel()->mergeCells('E' . $excelRow . ':J' . $excelRow);
                $style = $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow);
                $font = $style->getFont();
                $font->setBold(true);
                $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                foreach (range('E', 'J') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'CAF4FF'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;

                $this->getActiveSheetExcel()->fromArray($this->generateCheckCashReportHeader, null, 'E' . $excelRow);

                foreach (range('E', 'L') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;

                $checkNumber = '';
                $arDs = '';

                $repCheckNumber = Checks::where('checks_id', '=', $queryReplaced?->rep_check_id)->first();

                $repArDs = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
                    ->where('new_ds_checks.checks_id', '=', $queryReplaced?->rep_check_id)
                    ->first();

                if ($repCheckNumber == null) {
                    // nothing
                } else {
                    $checkNumber = $repCheckNumber->check_no;
                }
                if ($repArDs == null) {
                    //nothing
                } else {
                    $arDs = $repArDs->ds_no;
                }

                $dataCheckCollection[] = [
                    $queryReplaced->date_time ?? null,
                    $queryReplaced->check_amount_paid ?? null,
                    $checkNumber ?? null,
                    $queryReplaced->cash ?? null,
                    $queryReplaced->penalty ?? null,
                    $arDs ?? null,
                    $queryReplaced->reason ?? null,
                    $item->name ?? null,
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($dataCheckCollection, null, "E$excelRow");

                foreach (range('E', 'L') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow++;

                $this->getActiveSheetExcel()->fromArray($this->generateReportHeaderCheckDetails, null, 'B' . $excelRow);
                foreach (range('B', 'M') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $excelRow++;

                $replaceCheck = Checks::where('checks_id', $repCheckNumber?->checks_id)
                    ->join('customers', 'customers.customer_id', '=', 'checks.customer_id')
                    ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
                    ->join('department', 'department.department_id', '=', 'checks.department_from')
                    ->first();

                $innerDataDetailsCollection[] = [
                    $replaceCheck->check_received ?? null,
                    $replaceCheck->check_date ?? null,
                    $replaceCheck->account_no ?? null,
                    $replaceCheck->account_name ?? null,
                    $replaceCheck->bankbranchname ?? null,
                    $replaceCheck->fullname ?? null,
                    $replaceCheck->approving_officer ?? null,
                    $replaceCheck->check_class ?? null,
                    $replaceCheck->check_category ?? null,
                    $replaceCheck->department ?? null,
                    $replaceCheck->check_no ?? null,
                    $replaceCheck->check_amount ?? null,
                ];

                $this->spreadsheet->getActiveSheet()->fromArray($innerDataDetailsCollection, null, "B$excelRow");

                foreach (range('B', 'M') as $col) {
                    $cell = $col . $excelRow;
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow += 2;
            } elseif ($item->mode == 'PARTIAL') {
                $this->getActiveSheetExcel()->setCellValue('E' . $excelRow, 'REPLACEMENT MODE: PARTIAL');
                $this->getActiveSheetExcel()->mergeCells('E' . $excelRow . ':J' . $excelRow);
                $style = $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow);
                $font = $style->getFont();
                $font->setBold(true);
                $this->getActiveSheetExcel()->getStyle('E' . $excelRow . ':J' . $excelRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                foreach (range('C', 'L') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'CAF4FF'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }
                $excelRow++;
                $this->getActiveSheetExcel()->fromArray($this->generatePartialReportHeader, null, 'C' . $excelRow);
                foreach (range('C', 'K') as $col) {
                    $cell = $col . $excelRow;
                    $fillColor = [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EEEEEE'],
                    ];
                    $this->getActiveSheetExcel()->getStyle($col . $excelRow)->applyFromArray($this->border);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

                $partialQuery = NewCheckReplacement::join('users', 'users.id', '=', 'new_check_replacement.user')
                    ->where('new_check_replacement.checks_id', $item->checks_id)
                    ->where('new_check_replacement.mode', 'PARTIAL')
                    ->get();

                $cash = 0;
                $count = 0;

                $amount = 0;
                $balance1 = 0;
                $balance = 0;

                $partialQuery->each(function ($item) use (&$count, &$cash, &$balance1, &$balance, &$amount, &$excelRow) {
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

                $excelRow += 2;
            }

            RedeemCheckReportEvents::dispatch('Generating Redeem Checks excel... ', ++$progressCount, $itemCount, Auth::user());

        });
        // dd(1);
        foreach (range('A', 'M') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        $filename = $bunitHeader . ' Report from ' . date('F j Y', strtotime($dateRange[0])) . ' to ' . date('F j Y', strtotime($dateRange[1])) . '.xlsx';
        // $filename = 'Sample' . '.xlsx';
        $filePath = storage_path('app/' . $filename);

        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/AccountingReportPartials/RedeemPdcCheckReportResult', [
            'downloadExcel' => $downloadExcel,
            'url' => $url
        ]);

    }

}
