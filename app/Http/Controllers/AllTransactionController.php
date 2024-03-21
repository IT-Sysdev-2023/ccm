<?php

namespace App\Http\Controllers;

use App\Events\ExcelGenerateEvents;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\Currency;
use App\Models\NewCheckReplacement;
use App\Models\NewSavedChecks;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use Illuminate\Support\Facades\Date;
use App\Models\NewBounceCheck;

class AllTransactionController extends Controller
{
    public function getCheckManualEntry(Request $request)
    {
        $currency = Currency::orderBy('currency_name')->get();
        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();
        $check_class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('checks.is_manual_entry', true)
            ->where('new_saved_checks.status', '')
            ->where('checks.check_no', 'LIKE', '%' . $request->searchQuery . '%')
            ->orderBy('checks.check_received')
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) {
            $value->type = Date::parse($value->check_date)->lessThanOrEqualTo(today()) ? 'DATED' : 'POST DATED';
            return $value;
        });


        return Inertia::render('Transaction/CheckManualEntry', [
            'data' => $data,
            'columns' => ColumnsHelper::$check_manual_column,
            'currency' => $currency,
            'category' => $category,
            'check_class' => $check_class,
        ]);
    }
    public function checkManualEntryStore(Request $request)
    {
        // dd($request->all());

        $request->validate(
            [
                'accountnumber' => 'required',
                'checkdate' => 'required|date',
                'currency' => 'required',
                'checkfrom' => 'required',
                'accountname' => 'required',
                'customer' => 'required',
                'checkamount' => 'required|numeric',
                'checkclass' => 'required',
                'checknumber' => 'required',
                'bankname' => 'required',
                'checkreceived' => 'required|date',
                'checkcategory' => 'required',
                'approvingOfficer' => 'required',
            ],
            [
                'accountnumber.required' => 'The account number field is required.',
                'checkdate.required' => 'The check date field is required.',
                'checkdate.date' => 'The check date must be a valid date format.',
                'currency.required' => 'The currency field is required.',
                'checkfrom.required' => 'The check from field is required.',
                'accountname.required' => 'The account name field is required.',
                'customer.required' => 'The customer field is required.',
                'checkamount.required' => 'The check amount field is required.',
                'checkamount.numeric' => 'The check amount must be a numeric value.',
                'checkclass.required' => 'The check class field is required.',
                'checknumber.required' => 'The check number field is required.',
                'bankname.required' => 'The bank name field is required.',
                'checkreceived.required' => 'The check received field is required.',
                'checkreceived.date' => 'The check received is required.',
                'checkcategory.required' => 'The check category field is required.',
                'approvingOfficer.required' => 'The approving officer field is required.',
            ]
        );
        $checkType = "";

        if ($request->checkdate > today()->toDateString()) {
            $checkType = "POST DATED";
        } else {
            $checkType = "DATED CHECK";

        }
        DB::transaction(function () use ($request, $checkType) {
            $data = Checks::create([
                'businessunit_id' => $request->user()->businessunit_id,
                'check_type' => $checkType,
                'check_status' => "CLEARED",
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
                'check_no' => $request->checknumber,
                'customer_id' => $request->customer,
                'bank_id' => $request->bankname,
                'department_from' => $request->check_from,
                'currency_id' => $request->currency,
                'check_date' => $request->checkdate,
                'check_amount' => NumberHelper::float($request->checkamount),
                'check_class' => $request->checkclass,
                'check_category' => $request->checkcategory,
                'check_received' => $request->checkreceived,
                'account_no' => $request->accountnumber,
                'account_name' => $request->accountname,
                'approving_officer' => $request->approvingOfficer,
                'is_manual_entry' => 1,
                'checksreceivingtransaction_id' => 0,
                'check_bounced_id' => 0,
                'is_exist' => 0,
            ]);

            NewSavedChecks::create([
                'checks_id' => $data->checks_id,
                'check_type' => $request->checkdate,
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
            ]);

        });
        return redirect()->back();

    }
    public function getMergeChecks(Request $request)
    {
        $currency = Currency::orderBy('currency_name')->get();
        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();
        $check_class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

        $data = NewSavedChecks::joinChecksCustomer()->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('check_date', '>', 'check_received')
            ->paginate(500)->withQueryString();

        return Inertia::render('Transaction/MergeChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$merge_checks_column,
            'currency' => $currency,
            'category' => $category,
            'check_class' => $check_class,
        ]);
    }
    public function getMergeCheckStore(Request $request)
    {
        dd($request->all());


        // $request->validate(
        //     [
        //         'accountnumber' => 'required',
        //         'checkdate' => 'required|date',
        //         'currency' => 'required',
        //         'checkfrom' => 'required',
        //         'accountname' => 'required',
        //         'customer' => 'required',
        //         'checkamount' => 'required|numeric',
        //         'checkclass' => 'required',
        //         'checknumber' => 'required',
        //         'bankname' => 'required',
        //         'checkreceived' => 'required|date',
        //         'checkcategory' => 'required',
        //         'approvingOfficer' => 'required',
        //         'penalty' => 'required|numeric',
        //         'reason' => 'required',
        //     ],
        //     [
        //         'accountnumber.required' => 'The account number field is required.',
        //         'checkdate.required' => 'The check date field is required.',
        //         'checkdate.date' => 'The check date must be a valid date format.',
        //         'currency.required' => 'The currency field is required.',
        //         'checkfrom.required' => 'The check from field is required.',
        //         'accountname.required' => 'The account name field is required.',
        //         'customer.required' => 'The customer field is required.',
        //         'checkamount.required' => 'The check amount field is required.',
        //         'checkamount.numeric' => 'The check amount must be a numeric value.',
        //         'checkclass.required' => 'The check class field is required.',
        //         'checknumber.required' => 'The check number field is required.',
        //         'bankname.required' => 'The bank name field is required.',
        //         'checkreceived.required' => 'The check received field is required.',
        //         'checkreceived.date' => 'The check received is required.',
        //         'checkcategory.required' => 'The check category field is required.',
        //         'approvingOfficer.required' => 'The approving officer field is required.',
        //         'penalty.required' => 'The penalty field is required.',
        //         'penalty.numeric' => 'The penalty should be a number',
        //         'reason.required' => 'The reason field is required.',
        //     ]
        // );

        $checkType = '';

        if ($request->checkdate > today()->toDateString()) {
            $checkType = "POST DATED";
        } else {
            $checkType = "DATED CHECK";
        }

        DB::transaction(function () use ($request, $checkType) {
            $data = Checks::create([
                'businessunit_id' => $request->user()->businessunit_id,
                'check_type' => $checkType,
                'check_status' => "CLEARED",
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
                'check_no' => $request->checknumber,
                'customer_id' => $request->customer,
                'bank_id' => $request->bankname,
                'department_from' => $request->check_from,
                'currency_id' => $request->currency,
                'check_date' => $request->checkdate,
                'check_amount' => NumberHelper::float($request->checkamount),
                'check_class' => $request->checkclass,
                'check_category' => $request->checkcategory,
                'check_received' => $request->checkreceived,
                'account_no' => $request->accountnumber,
                'account_name' => $request->accountname,
                'approving_officer' => $request->approvingOfficer,
                'is_manual_entry' => 1,
                'checksreceivingtransaction_id' => 0,
                'check_bounced_id' => 0,
                'is_exist' => 0,
            ]);

            NewSavedChecks::create([
                'checks_id' => $data->checks_id,
                'check_type' => $request->checkdate,
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
            ]);

            foreach ($request->checkedItems as $check) {

                $mergeCheckAmount = NumberHelper::float($request->checkamount);
                $totalPenalty = NumberHelper::float($request->penalty);
                $percentage = $mergeCheckAmount / $totalPenalty;

                $penaltyIndividual = $check['check_amount'] * $percentage;

                NewCheckReplacement::create([
                    'checks_id' => $check['checks_id'],
                    'rep_check_id' => $data->checks_id,
                    'check_amount' => $check['check_amount'],
                    'check_amount_paid' => NumberHelper::float($request->checkamount),
                    'status' => 'REDEEMED',
                    'mode' => 'CHECK',
                    'user' => $request->user()->id,
                    'date_time' => $request->checkreceived,
                    'reason' => $request->reason,
                    'penalty' => $penaltyIndividual,
                ]);

                NewSavedChecks::where('checks_id', $check['checks_id'])->update(['status' => 'REDEEM']);

            }


        });


    }
    public function getBounceChecks(Request $request)
    {
        $data = NewBounceCheck::join('checks', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('new_bounce_check.status', '=', '')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->paginate(10);

        // dd($data);
        return Inertia::render('Transaction/BounceChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$bounced_checks_columns,
        ]);
    }
    public function getCheckReplace(Request $request)
    {
        $q = NewCheckReplacement::joinCheckReplacementCustomer()
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('new_check_replacement.status', '!=', '')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            // ->where('mode', 'PARTIAL')
            ->orderBy('new_check_replacement.id', 'desc')
            ->select('*', 'new_check_replacement.date_time');

        $q = match ($request->getMode) {
            '1' => $q->where('mode', 'CHECK'),
            '2' => $q->where('mode', 'CHECK & CASH'),
            '3' => $q->where('mode', 'CASH'),
            '5' => $q->where('mode', 'PARTIAL'),
            '4' => $q->where('mode', 'RE-DEPOSIT'),
            default => $q
        };
        $data = $q->paginate(10);

        return Inertia::render('Transaction/CheckReplace', [
            'data' => $data,
            'columns' => ColumnsHelper::$check_replace_columns,
            'getModeProps' => $request->getMode
        ]);
    }
    public function getPartialPayment(Request $request)
    {
        $data = NewCheckReplacement::joinCheckReplacementCustomer()
            // ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('checks.check_status', 'PARTIAL')
            ->where('new_check_replacement.mode', 'PARTIAL')
            ->select('checks.*', 'customers.*', 'new_check_replacement.status', 'new_check_replacement.*')
            // ->groupBy('new_check_replacement.checks_id')
            ->paginate(10);

        $data->transform(function ($value) {

            $check_replacement = NewCheckReplacement::checksMode($value->checks_id)
                ->selectRaw('SUM(new_check_replacement.cash) as paid_cash, SUM(new_check_replacement.check_amount_paid) as paid_check')
                ->first();

            if ($value->bounce_id !== null) {
                $bounceDate = NewBounceCheck::where('id', $value->bounce_id)->first();
                $bounceDate = Date::parse($bounceDate->date_time)->toFormattedDateString();
            } else {
                $bounceDate = 'REDEEMED ' . Date::parse($value->date_time)->toFormattedDateString();
            }

            $sub_total = collect($check_replacement)->sum();
            $amount_balance = $value->check_amount - $sub_total;

            $value->bounce_date = $bounceDate;
            $value->paid_cash = NumberHelper::currency($check_replacement->paid_cash);
            $value->check_amount = NumberHelper::currency($value->check_amount);
            $value->paid_check = NumberHelper::currency($check_replacement->paid_check);
            $value->amount_balance = NumberHelper::currency($amount_balance);
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            return $value;
        });



        return Inertia::render('Transaction/PartialPayments', [
            'data' => $data,
            'columns' => ColumnsHelper::$partial_payment_columns,
        ]);
    }
    public function getDatedPdcReports(Request $request)
    {
        $data = NewSavedChecks::filter($request->only(['status']), $request->user()->businessunit_id)
            ->select('*', 'checks.check_type as check-type', 'new_saved_checks.check_type as new_check_type')
            ->whereBetween('checks.check_received', [$request->date_from, $request->date_to])
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) use ($request) {
            $typeStatus = '';
            if ($request->status == '1' || $request->status == '2') {
                if ($value->new_check_type <= today()) {
                    $typeStatus = 'DATED';
                } else {
                    $typeStatus = 'POST DATED';
                }
            }

            $value->status = $typeStatus;

            return $value;
        });
        // dd((!empty($request->date_from) && !empty($request->date_to)) ? [$request->date_from, $request->date_to] : '', );

        return Inertia::render('Transaction/DatedCheckPdcReports', [
            'filters' => $request->all('date_from', 'date_to', 'status'),
            'data' => $data,
            'columns' => ColumnsHelper::$datedpdcreportcheck_columns,
            'status' => $request->status,
            'dateRangeValue' => (!empty ($request->date_from) && !empty ($request->date_to)) ? [$request->date_from, $request->date_to] : '',
        ]);
    }
    public function generate_report(Request $request)
    {
        $dateRange = [$request->date_from, $request->date_to];

        $data = NewSavedChecks::filter($request->only(['status']), $request->user()->businessunit_id)
            ->select('*', 'checks.check_type as check-type', 'new_saved_checks.check_type as new_check_type', 'department')
            ->whereBetween('checks.check_received', $dateRange)
            ->cursor()
            ->groupBy('department');

        return (
            new TransactionService())
            ->record($data)
            ->setStatus($request->status)
            ->writeResult($dateRange);
    }

    public function getDuepdcReports(Request $request)
    {
        $dateRange = [$request->date_from, $request->date_to];
        $buname = BusinessUnit::where('businessunit_id', $request->user()->businessunit_id)->first();



        $data = NewSavedChecks::filterDPdcReports($dateRange, $request->user()->businessunit_id)->paginate(10)->withQueryString();

        return Inertia::render('Transaction/DuePostDatedCheckReport', [
            'data' => $data,
            'columns' => ColumnsHelper::$due_pdc_reports_columns,
            'dateRangeValue' => $dateRange[0] === null ? null : $dateRange,
            'buname' => $buname,
        ]);
    }
    public function generateExcelDuePdcReports(Request $request)
    {
        $buname = BusinessUnit::where('businessunit_id', $request->user()->businessunit_id)->first();
        $dateRange = [$request->date_from, $request->date_to];
        $data = NewSavedChecks::filterDPdcReports($dateRange, $request->user()->businessunit_id)->cursor();

        return (new TransactionService())
            ->record($data)
            ->writeResultDuePdc($dateRange, $buname);
    }
}
