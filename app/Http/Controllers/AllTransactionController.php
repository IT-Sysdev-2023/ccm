<?php

namespace App\Http\Controllers;

use App\Models\NewCheckReplacement;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
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
        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('checks.is_manual_entry', true)
            ->where('new_saved_checks.status', '')
            ->where('checks.check_no', 'LIKE', '%' . $request->searchQuery . '%')
            ->orderBy('checks.check_received')
            ->paginate(20);

        $data->transform(function ($value) {
            $value->type = Date::parse($value->check_date)->lessThanOrEqualTo(today()) ? 'DATED' : 'POST DATED';
            return $value;
        });


        return Inertia::render('Transaction/CheckManualEntry', [
            'data' => $data,
            'columns' => ColumnsHelper::$check_manual_column,
        ]);
    }
    public function getMergeChecks(Request $request)
    {
        $data = NewSavedChecks::joinChecksCustomer()->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('check_date', '>', 'check_received')
            ->paginate(15);

        return Inertia::render('Transaction/MergeChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$merge_checks_column,
        ]);
    }
    public function getBounceChecks(Request $request)
    {
        $data = NewBounceCheck::join('checks', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('new_bounce_check.status', '=', '')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->paginate(15);

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
        $data = $q->paginate(15);

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

        // dd($request->all());

        $data = NewSavedChecks::filter($request->only(['date_from', 'date_to', 'status']), $request->user()->businessunit_id)
            ->paginate(10)->withQueryString();

        // $dateRangeValue = [$request->date_from, $request->date_to];


        return Inertia::render('Transaction/DatedCheckPdcReports', [
            'filters' => $request->all('date_from', 'date_to', 'status'),
            'data' => $data,
            'columns' => ColumnsHelper::$datedpdcreportcheck_columns,
            // 'statusReport' => $request->status,
            // 'dateRange' => $dateRangeValue,
        ]);
    }
}
