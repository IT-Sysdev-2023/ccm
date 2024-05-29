<?php

namespace App\Services;

use App\Events\AccountingDatedPdcReportEvents;
use App\Helper\Excel\ExcelWriter;
use App\Helper\NumberHelper;
use App\Models\NewBounceCheck;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DatedPdcCheckServices extends ExcelWriter
{
    protected $record;
    protected $border;
    protected $borderFBN;

    public function __construct()
    {
        parent::__construct();

        $this->headerRow = array([
            "DATE RECIEVED",
            "CHECK DATE",
            "BANK",
            "ACCOUNT NUMBER",
            "ACCOUNT NAME",
            "CUSTOMER",
            "APPROVING OFFICER",
            "CHECK CLASS",
            "CHECK CATEGORY",
            "CHECK FROM",
            "CHECK NO.",
            "AMOUNT",
            "DEPOSIT STATUS",
            "DS_NO",
            "DEPOSIT DATE",
        ]);

        $this->border = $this->initializedBorder();
        $this->borderFBN = $this->initializedBorderFontBoldNone();

    }

    public function record($data)
    {
        $this->record = $data;

        return $this;
    }

    public function writeResult($dataFrom, $dataStatus, $dateRange, $dataType, $bunit, $redirections)
    {

        // dd($dateRange);

        $header = 7;
        $masterHeader = 2;
        $bStatusHeader = '';
        $dateHeader = [];
        // dd($dataFrom, $dataStatus, $dateRange, $dataType);

        if (($dataStatus == '0' || $dataStatus == null) && $dataType == '1' && ($dateRange == null || $dateRange[0] == 'Invalid Date' || $dateRange[0] == null )) {

            $bStatusHeader = 'ALL DATED CHECKS';

            $dateHeader = " As of . " . today()->toFormattedDateString();

        } else if (($dataStatus == '0' || $dataStatus == null) && $dataType == '2' && ($dateRange == null || $dateRange[0] == 'Invalid Date' || $dateRange[0] == null  ) && $dataFrom == '') {

            $bStatusHeader = 'ALL PENDING CHECKS';

            $dateHeader = " As of . " . today()->toFormattedDateString();

        } else if ($dataStatus == '1' && $dataType == '1' && ($dateRange == null || $dateRange[0] == 'Invalid Date' || $dateRange[0] == null )) {

            $bStatusHeader = 'PENDING DATED CHECKS';

            $dateHeader = " As of . " . today()->toFormattedDateString();

        } else if ($dataStatus == '2' && $dataType == '1' && ($dateRange == null || $dateRange[0] == 'Invalid Date' || $dateRange[0] == null )) {

            $bStatusHeader = 'POST DATED PENDING CHECKS';

            $dateHeader = " As of . " . today()->toFormattedDateString();

        } else if ($dataStatus == '1' && $dataType == '2' && ($dateRange == null || $dateRange[0] == 'Invalid Date' || $dateRange[0] == null )) {

            $bStatusHeader = 'DEPOSITED DATED CHECKS';

            $dateHeader = " As of . " . today()->toFormattedDateString();

        } else if ($dataStatus == '2' && $dataType == '2' && ($dateRange == null || $dateRange[0] == 'Invalid Date' || $dateRange[0] == null )) {
            $bStatusHeader = 'POST DATED DEPOSITED CHECKS';

            $dateHeader = " As of . " . today()->toFormattedDateString();

        } else if (($dataStatus == '0' || $dataStatus == null) && $dataType == '1' && ($dateRange != null || $dateRange != 'Invalid Date')) {
            $bStatusHeader = 'ALL DATED CHECKS';
            $dateHeader = "From " . Date::parse($dateRange[0])->toFormattedDateString() . " to " . Date::parse($dateRange[1])->toFormattedDateString();
        } else if (($dataStatus == '0' || $dataStatus == null) && $dataType == '1' && ($dateRange != null || $dateRange != 'Invalid Date')) {
            $bStatusHeader = 'ALL PENDING CHECKS';
            $dateHeader = "From " . Date::parse($dateRange[0])->toFormattedDateString() . " to " . Date::parse($dateRange[1])->toFormattedDateString();
        } else if ($dataStatus == '1' && $dataType == '1' && $dateRange != null && $dateRange != 'Invalid Date') {
            $bStatusHeader = 'PENDING DATED CHECKS';
            $dateHeader = "From " . Date::parse($dateRange[0])->toFormattedDateString() . " to " . Date::parse($dateRange[1])->toFormattedDateString();

        } else if ($dataStatus == '2' && $dataType == '1' && ($dateRange != null || $dateRange != 'Invalid Date')) {
            $bStatusHeader = 'POST DATED PENDING CHECKS';
            $dateHeader = "From " . Date::parse($dateRange[0])->toFormattedDateString() . " to " . Date::parse($dateRange[1])->toFormattedDateString();
        } else if ($dataStatus == '1' && $dataType == '2' && ($dateRange != null || $dateRange != 'Invalid Date')) {
            $bStatusHeader = 'DEPOSITED DATED CHECKS';
            $dateHeader = "From " . Date::parse($dateRange[0])->toFormattedDateString() . " to " . Date::parse($dateRange[1])->toFormattedDateString();

        } else if ($dataStatus == '2' && $dataType == '2' && ($dateRange != null || $dateRange != 'Invalid Date')) {
            $bStatusHeader = 'POST DATED DEPOSITED CHECKS';
            $dateHeader = "From " . Date::parse($dateRange[0])->toFormattedDateString() . " to " . Date::parse($dateRange[1])->toFormattedDateString();
        }

        $this->getActiveSheetExcel()->setCellValue('A' . $masterHeader, $bunit[0]->bname);
        $this->getActiveSheetExcel()->mergeCells('A' . $masterHeader . ':O' . $masterHeader);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $masterHeader . ':O' . $masterHeader);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $masterHeader . ':O' . $masterHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $masterHeader++;

        $this->getActiveSheetExcel()->setCellValue('A' . $masterHeader, $bStatusHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $masterHeader . ':O' . $masterHeader);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $masterHeader . ':O' . $masterHeader);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $masterHeader . ':O' . $masterHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $masterHeader++;

        $this->getActiveSheetExcel()->setCellValue('A' . $masterHeader, $dateHeader);
        $this->getActiveSheetExcel()->mergeCells('A' . $masterHeader . ':O' . $masterHeader);
        $style = $this->getActiveSheetExcel()->getStyle('A' . $masterHeader . ':O' . $masterHeader);
        $font = $style->getFont();
        $font->setBold(true);
        $this->getActiveSheetExcel()->getStyle('A' . $masterHeader . ':O' . $masterHeader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $masterHeader++;

        if ($dataType == '2') {
            array_push($this->headerRow[0], "PDC GAP(DAYS)");
            $concatenatedString = implode(", ", $this->headerRow[0]);
        }

        $this->getActiveSheetExcel()->fromArray($this->headerRow, null, 'A' . $header);

        if ($dataType == '2') {
            foreach (range('A', 'P') as $col) {
                $cell = $col . $header;

                $fillColor = [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'CAF4FF'],
                ];

                $this->getActiveSheetExcel()->getStyle($col . $header)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);

            }
        } else {
            foreach (range('A', 'O') as $col) {
                $cell = $col . $header;

                $fillColor = [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'CAF4FF'],
                ];

                $this->getActiveSheetExcel()->getStyle($col . $header)->applyFromArray($this->border);
                $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->setFillType($fillColor['fillType']);
                $this->getActiveSheetExcel()->getStyle($cell)->getFill()->getStartColor()->setRGB($fillColor['startColor']['rgb']);

            }
        }
        foreach (range('A', 'P') as $col) {
            $this->getActiveSheetExcel()->getColumnDimension($col)->setAutoSize(true);
        }

        $header++;
        $startCount = 1;
        $itemCount = count($this->record);

        $this->record->each(function ($item) use (&$dataType, &$header, &$startCount, $itemCount) {

            $depositedStatus = NewDsChecks::where('checks_id', '=', $item->checks_id)
                ->select('ds_no', 'status', 'date_deposit')
                ->first();
            $dsNumber = '';
            $depositDdate = '';
            $days = '';

            if ($depositedStatus == null) {
                $depositedStatus = "PENDING DEPOSIT";
            } else if ($depositedStatus == 'BOUNCED') {
                $bounceStatus = NewBounceCheck::where('checks_id', '=', $report->checks_id)
                    ->orderBy('new_bounce_check.id', 'desc')
                    ->first();
                if ($bounceStatus == 'SETTLE CHECK') {
                    $replacmentType = NewCheckReplacement::where('bounce_id', $bounce_status->id)
                        ->orderBy('new_check_replacement.date_time', 'desc')
                        ->first();
                    if ($replacmentType->mode == 'RE-DEPOSIT') {
                        $reDepositedStatus = NewDsChecks::where('checks_id', '=', $report->checks_id)
                            ->select('ds_no', 'status', 'date_deposit')
                            ->orderBy('id', 'desc')
                            ->first();
                        $depositedStatus = 'CLEARED';
                        $dsNumber = $reDepositedStatus->ds_no;
                        $depositDdate = $reDepositedStatus->date_deposit;
                    } else {
                        $depositedStatus = 'Replaced to' . $replacmentType->mode;
                    }
                } else {
                    $depositedStatus = 'BOUNCE PENDING CHECK';
                }
            } else {
                $dsNumber = $depositedStatus->ds_no;
                $depositDdate = $depositedStatus->date_deposit;
                $depositedStatus = 'CHECK CLEARED';
            }

            $redeem = NewCheckReplacement::where('checks_id', $item->checks_id)->first();

            if ($redeem == null) {

            } else {
                if ($redeem->status == 'REDEEMED') {
                    $depositedStatus = 'REDEEMED';
                }
            }

            if ($dataType == '2') {
                $firstDate = strtotime($item->check_date);
                $secondDate = strtotime($item->check_received);

                $seconds = $firstDate - $secondDate;

                $days = $seconds / 86400;
            } else {

            }

            $dataRecordCollection[] = [
                date('F-d-Y', strtotime($item->check_date)),
                date('F-d-Y', strtotime($item->check_received)),
                $item->bankbranchname,
                $item->account_no,
                $item->account_name,
                $item->fullname,
                $item->approving_officer,
                $item->check_class,
                $item->check_category,
                $item->department,
                $item->check_no,
                NumberHelper::currency($item->check_amount),
                $depositedStatus,
                $dsNumber,
                date('F-d-Y', strtotime($depositDdate)),
                $days,
            ];

            $this->spreadsheet->getActiveSheet()->fromArray($dataRecordCollection, null, "A$header");

            if ($dataType == '2') {
                foreach (range('A', 'P') as $col) {
                    $cell = $col . $header;
                    $this->getActiveSheetExcel()->getStyle($col . $header)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

            } else {
                foreach (range('A', 'O') as $col) {
                    $cell = $col . $header;
                    $this->getActiveSheetExcel()->getStyle($col . $header)->applyFromArray($this->borderFBN);
                    $this->getActiveSheetExcel()->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setSize(8);
                    $this->getActiveSheetExcel()->getStyle($cell)->getFont()->setName('Arial');
                }

            }
            $header++;

            AccountingDatedPdcReportEvents::dispatch('Generating Dated and Post Dated Check Reports.. ', $startCount++, $itemCount, Auth::user());

        });



        $filename = $bunit[0]->bname . '-' . $bStatusHeader . $dateHeader . '.xlsx';

        $filePath = storage_path('app/' . $filename);

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        return Inertia::render('Components/AccountingReportPartials/DatedPdcCheckResult', [
            'downloadExcel' => $downloadExcel,
            'redirect' => $redirections
        ]);

    }
}
