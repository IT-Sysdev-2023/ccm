<?php
namespace App\Services;

use App\Events\ExcelGenerateEvents;
use App\Helper\Excel\ExcelWriter;
use App\Helper\NumberHelper;
use App\Models\NewBounceCheck;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class TransactionService extends ExcelWriter
{
    protected $record;

    protected bool $status;

    private array $border;

    protected \Illuminate\Support\Collection $generateReportHeader;

    protected array $table_columnsHeader;
    public function __construct()
    {
        parent::__construct();

        $this->generateReportHeader = collect(
            [
                "NO",
                "CUSTOMER NAME",
                "CHECK NO",
                "CHECK DATE",
                "AMOUNT",
                "ACCOUNT NO",
                "ACCOUNT NAME",
                "BANK NAME",
            ]
        );


        $this->table_columnsHeader = array(
            "NO.",
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
            "PDC GAP(DAYS)",
        );

        $this->border = $this->initializedBorder();

    }

    function record($data): self
    {
        $this->record = $data;

        return $this;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status === '1' ? true : false;
        return $this;
    }

    protected function transformColumn(): array
    {
        if ($this->status) {
            $headerTitle = 'Dated Check Report';
            $headerRow = $this->generateReportHeader;
        } else {
            $headerTitle = 'Post Dated Check Report';
            $headerRow = $this->generateReportHeader->concat(['STATUS']);
        }
        return ['headerTitle' => $headerTitle, 'headerRow' => $headerRow];
    }

    private function headerStyle($title): void
    {
        $this->getCellSetValue('E1', 'Status Type : ' . ' ' . $title);
        $this->getCellSetValue('E2', 'Date : ' . ' ' . today()->toFormattedDateString());

        $this->getStyleGetFontSetBold('E1');
        $this->getStyleGetFontSetBold('E2');
    }
    public function writeResult(array $dateRange)
    {

        $this->executionTime();

        $grandTotal = 0;
        $header = $this->transformColumn();
        $this->headerStyle($header['headerTitle']);

        $recordCount = $this->record->count();
        $recordkeys = $this->record->keys()->all();

        $recordSearch = array_combine(range(1, count($recordkeys)), array_values($recordkeys));

        $excel_row = 5;

        ExcelGenerateEvents::dispatch('-', 'Generating Excel of', 0, $this->record->count(), Auth::user(), 0, $recordCount);

        sleep(2);

        $this->record->each(function ($item, string $department) use (&$excel_row, $header, &$grandTotal, $recordCount, $recordSearch) {

            $countTable = 1;
            $progressCount = 0;

            $this->getActiveSheetExcel()->mergeCells('A' . $excel_row . ':I' . $excel_row);

            $this->getActiveSheetExcel()->setCellValue('A' . $excel_row, $department);
            $this->setHorizontalAlignment('A' . $excel_row);
            $this->getStyleGetFontSetBold('A' . $excel_row);

            $excel_row++;

            $this->getActiveSheetExcel()->fromArray($header['headerRow']->toArray(), null, 'A' . $excel_row);
            $this->getStyleGetFontSetBold('A' . $excel_row . ':I' . $excel_row);

            if ($this->status) {
                $this->getActiveSheetExcel()->getStyle('A' . $excel_row . ':H' . $excel_row)->applyFromArray($this->border);
            } else {
                $this->getActiveSheetExcel()->getStyle('A' . $excel_row . ':I' . $excel_row)->applyFromArray($this->border);
            }


            $excel_row++;
            $reportCollection = [];
            $subtotal = 0;

            $item->each(function ($value, $key) use (&$countTable, &$progressCount, &$subtotal, &$reportCollection, $department, $item, $recordCount, &$excel_row, $recordSearch) {
                // dd(1);
                $statusType = '';

                if (!$this->status) {
                    $statusType = 'POST-DATED';
                    if ($value->check_date <= date('Y-m-d')) {
                        $statusType = 'POST-DATED DUE';
                    }
                }



                $reportCollection[] = [
                    $countTable++,
                    $value->fullname,
                    $value->check_no,
                    Date::parse($value->new_check_type)->format('M-d-Y'),
                    NumberHelper::format($value->check_amount),
                    $value->account_no,
                    $value->account_name,
                    $value->bankbranchname,
                    $statusType
                ];

                $subtotal += $value->check_amount;

                $postion = array_search($department, $recordSearch);

                ExcelGenerateEvents::dispatch($department, 'Generating Excel of', ++$progressCount, $item->count(), Auth::user(), $postion, $recordCount);
            });

            $this->setCellValueSheet('E' . ($excel_row + count($item) + 1), 'Subtotal:');
            $this->setCellValueSheet('F' . ($excel_row + count($item) + 1), NumberHelper::format($subtotal));

            $this->getStyleGetFontSetBold('E' . ($excel_row + count($item) + 1));
            $this->getStyleGetFontSetBold('F' . ($excel_row + count($item) + 1));

            $this->getActiveSheetExcel()->fromArray($reportCollection, null, "A$excel_row");


            if ($this->status) {
                foreach (range('A3', 'H3') as $column) {
                    self::setColumnDimension($this->spreadsheet, $column, $excel_row);
                }
            } else {
                foreach (range('A3', 'I3') as $column) {
                    self::setColumnDimension($this->spreadsheet, $column, $excel_row);
                }
            }
            // Set borders for the data
            $highestRow = $excel_row + count($item);
            if ($this->status) {
                $highestColumn = 'H';
            } else {
                $highestColumn = 'I';
            }

            $dataRange = "A$excel_row:$highestColumn$highestRow";

            $this->getActiveSheetExcel()->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
            $this->getActiveSheetExcel()->getStyle($dataRange)->getFont()->setName('Fira Sans')->setSize(9);
            $grandTotal += $subtotal;

            // Increment row number for the next department
            $excel_row += count($item) + 4;

        });

        $this->setCellValueSheet('E' . ($excel_row), 'Grand Total:');
        $this->setCellValueSheet('F' . ($excel_row), number_format($grandTotal, 2));
        $this->getStyleGetFontSetBold('E' . ($excel_row));
        $this->getStyleGetFontSetBold('F' . ($excel_row));

        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($tempFilePath);

        $filename = $header['headerTitle'] . ' on ' . now()->format('M, d Y') . '.xlsx';
        $filePath = storage_path('app/' . $filename);


        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);

        sleep(1);


        return Inertia::render('Components/TransactionPartials/ResultDatedPdcreports', [
            'downloadExcel' => $downloadExcel,
            'dataRecord' => $this->record,
        ]);


    }

    public function writeResultDuePdc($dateRange, $businessUnit)
    {
        $this->executionTime();


        if ($dateRange && $dateRange[0] != null) {
            $date = Date::parse($dateRange[0])->toFormattedDateString() . ' To: ' . Date::parse($dateRange[1])->toFormattedDateString();
        } else {
            $date = today()->toFormattedDateString();
        }

        $this->getCellSetValue('B2', 'From : ' . $date);
        $this->getCellSetValue('B1', 'Status Type : ' . ' ' . $businessUnit->bname);
        $this->getStyleGetFontSetBold('E1');
        $this->getStyleGetFontSetBold('E2');
        $this->getStyleGetFontSetBold('B2');
        $this->getStyleGetFontSetBold('B1');

        $this->getActiveSheetExcel()->getStyle('A5:Q5')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ],
            ],
        ]);

        $column = 1;
        $countTable = 1;
        $progressCount = 0;
        $row = 6;

        $this->record->each(function ($item) use ($businessUnit, &$progressCount, &$reportCollection, &$spreadsheet, &$row, &$countTable) {
            $deposited_status = NewDsChecks::where('checks_id', $item->checks_id)
                ->select('ds_no', 'status', 'date_deposit')
                ->first();

            $ds_number = '';
            $deposit_date = '';
            $days = '';

            $checkDate = Date::parse($item->check_date);
            $checkRecieved = Date::parse($item->check_received);

            // Calculate the difference in days
            $days = $checkRecieved->diffInDays($checkDate);

            if ($deposited_status === null) {

                $deposited_status = 'PENDING DEPOSIT';

            } else if ($deposited_status->status === 'BOUNCED') {

                $bounce_status = NewBounceCheck::where('checks_id', $item->checks_id)
                    ->first();

                if ($bounce_status->status === 'SETTLE CHECK') {
                    $replacement_type = NewCheckReplacement::where('bounce_id', $bounce_status->id)
                        ->first();

                    if ($replacement_type->mode == 'RE-DEPOSIT') {
                        $redeposited_status = NewDsChecks::where('checks_id', '=', $item->checks_id)
                            ->orderBy('id', 'desc')
                            ->first();

                        $deposited_status = $replacement_type->mode . ' CLEARED';
                        $ds_number = $redeposited_status->ds_no;
                        $deposit_date = $redeposited_status->date_deposit;
                    } else {
                        $deposited_status = 'REPLACED TO ' . $replacement_type->mode;
                    }
                } else {
                    $deposited_status = 'BOUNCE PENDING CHECK';
                }
            } else {
                $ds_number = $deposited_status->ds_no;
                $deposit_date = $deposited_status->date_deposit;
                $deposited_status = 'CHECK CLEARED';
            }

            $redeem = NewCheckReplacement::where('checks_id', $item->checks_id)->first();

            if (!is_null($redeem)) {
                if ($redeem->status == 'REDEEMED') {
                    $deposited_status = 'REDEEMED';
                }
            }


            $reportCollection[] = [
                $countTable++,
                date('m-d-Y', strtotime($item->check_received)),
                date('m-d-Y', strtotime($item->check_date)),
                $item->bankbranchname,
                $item->account_no,
                $item->account_name,
                $item->fullname,
                $item->approving_officer,
                $item->check_class,
                $item->check_category,
                $item->department,
                $item->check_no,
                NumberHelper::format($item->check_amount),
                $deposited_status,
                $ds_number,
                $deposit_date,
                $days,
            ];

            ExcelGenerateEvents::dispatch($businessUnit->bname, 'Generating Excel to', ++$progressCount, $this->record->count(), Auth::user(), 12, 12);

            $this->getActiveSheetExcel()->fromArray($reportCollection, null, "A$row");

        });

        foreach (range('A3', 'Q3') as $column) {
            $this->getActiveSheetExcel()->getColumnDimension($column)->setAutoSize(true);
        }

        $highestRow = $row + count($this->record); // Determine the last row of data for this department

        $highestColumn = 'Q';

        $dataRange = "A$row:$highestColumn$highestRow";

        $this->getActiveSheetExcel()->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);


        $this->getActiveSheetExcel()->fromArray($this->table_columnsHeader, null, 'A5');

        $filename = $businessUnit->bname . ' on ' . now()->format('M, d Y') . '.xlsx';

        $filePath = storage_path('app/' . $filename);


        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);

        $downloadExcel = route('download.excel', ['filename' => $filename]);
        sleep(1);


        return Inertia::render('Components/TransactionPartials/ResultDuePostDatedCheckReport', [
            'downloadExcel' => $downloadExcel,
            'buname' => $businessUnit->bname,
            'dataCount' => $this->record->count(),
        ]);

    }
    public static function setColumnDimension($spreadsheet, $column, $excel_row): void
    {
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
