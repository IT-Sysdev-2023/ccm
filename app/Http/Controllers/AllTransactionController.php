<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Http\Requests\CashReplacementRequest;
use App\Http\Requests\CheckCashReplacementRequest;
use App\Http\Requests\CheckReDepositRequest;
use App\Http\Requests\CheckReplacementRequest;
use App\Http\Requests\ManualEntryStoreRequest;
use App\Http\Requests\MergeCheckStoreRequest;
use App\Http\Requests\PartialPaymentCashRequest;
use App\Http\Requests\PartialPaymentCheckRequest;
use App\Http\Requests\PartialPaymentRequest;
use App\Http\Requests\PartialReplaymentCheckRequest;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\Currency;
use App\Models\NewBounceCheck;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
    public function checkManualEntryStore(ManualEntryStoreRequest $request)
    {
        $request->validated();
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
            // ->where(function ($query) use ($request) {
            //     $query->where('checks.check_no', 'like', '%' . $request->searchQuery . '%')
            //         ->orWhere('checks.check_amount', 'like', '%' . $request->searchQuery . '%')
            //         ->orWhere('customers.fullname', 'like', '%' . $request->searchQuery . '%');
            //     return $query;
            // })
            ->whereAny([
                'checks.check_no',
                'checks.check_amount',
                'customers.fullname',
            ], 'LIKE', `'%' . $request->searchQuery . '%'`)
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
    public function getMergeCheckStore(MergeCheckStoreRequest $request)
    {
        $request->validated();

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
                    'bounce_id' => 0,
                    'cash' => 0,
                ]);

                NewSavedChecks::where('checks_id', $check['checks_id'])->update(['status' => 'REDEEM']);

            }

        });

        return redirect()->route('mergechecks.checks');

    }
    public function getBounceChecks(Request $request)
    {
        $currency = Currency::orderBy('currency_name')->get();
        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();
        $check_class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

        $data = NewBounceCheck::join('checks', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('new_bounce_check.status', '=', '')
            // ->where(function ($query) use ($request) {
            //     $query->where('checks.check_no', 'like', '%' . $request->searchQuery . '%')
            //         ->orWhere('checks.check_amount', 'like', '%' . $request->searchQuery . '%')
            //         ->orWhere('customers.fullname', 'like', '%' . $request->searchQuery . '%');
            //     return $query;
            // })
            ->whereAny([
                'checks.check_no',
                'checks.check_amount',
                'customers.fullname',
            ], 'LIKE', `'%' . $request->searchQuery . '%'`)
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->select('new_bounce_check.*', 'new_bounce_check.id as bounceId', 'checks.*', 'customers.*', 'banks.bankbranchname', 'department.department')
            ->paginate(10)->withQueryString();

        return Inertia::render('Transaction/BounceChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$bounced_checks_columns,
            'currency' => $currency,
            'category' => $category,
            'check_class' => $check_class,
        ]);
    }

    public function bouncedCashReplacement(CashReplacementRequest $request)
    {
        $request->validated();
        $data = Checks::where('checks_id', $request->rep_check_id)->first();

        DB::transaction(function () use ($request, $data) {
            NewCheckReplacement::create([
                'bounce_id' => $request->rep_bounce_id,
                'rep_check_id' => 0,
                'check_amount_paid' => 0,
                'checks_id' => $request->rep_check_id,
                'check_amount' => NumberHelper::float($data->check_amount),
                'cash' => NumberHelper::float($request->rep_cash_amount),
                'penalty' => NumberHelper::float($request->rep_cash_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'reason' => $request->rep_reason,
                'mode' => "BOUNCED",
                'status' => "CASH",
                'user' => Auth::user()->id,
                'date_time' => $request->rep_date,
            ]);

            Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'CASH', 'cash' => NumberHelper::float($request->rep_cash_amount)]);
            NewBounceCheck::where('id', $request->rep_bounce_id)->update(['status' => 'SETTLED CHECK']);
        });

        return redirect()->back();
    }
    public function bouncedCheckReplacement(CheckReplacementRequest $request)
    {
        $request->validated();

        if ($request->rep_check_date > today()->toDateString()) {
            $check_type = "POST DATED";
        } else {
            $check_type = "DATED CHECK";
        }

        DB::transaction(function () use ($request, $check_type) {
            $new_check_id = Checks::create([
                'checksreceivingtransaction_id' => 0,
                'businessunit_id' => $request->user()->businessunit_id,
                'check_bounced_id' => $request->rep_check_id,
                'date_time' => today()->toDateString(),
                'check_type' => $check_type,
                'user' => $request->user()->id,
                'check_status' => 'CLEARED',
                'check_no' => $request->checkNumber,
                'bank_id' => $request->bank_id,
                'department_from' => $request->checkFrom_id,
                'currency_id' => $request->currency_id,
                'check_date' => $request->rep_check_date,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_class' => $request->checkClass,
                'check_category' => $request->category,
                'check_received' => $request->rep_check_received,
                'account_name' => $request->accountname,
                'account_no' => $request->accountnumber,
                'approving_officer' => $request->approvingOfficer,
                'customer_id' => $request->customerId,
                'is_exist' => 0,
                'is_manual_entry' => 0,
            ]);

            NewSavedChecks::create([
                'checks_id' => $new_check_id->checks_id,
                'check_type' => $request->rep_check_date,
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
            ]);

            NewCheckReplacement::create([
                'bounce_id' => $request->rep_bounce_id,
                'checks_id' => $request->rep_check_id,
                'cash' => 0,
                'rep_check_id' => $new_check_id->checks_id,
                'reason' => $request->rep_reason,
                'penalty' => NumberHelper::float($request->rep_check_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                'mode' => "CHECK",
                'status' => "BOUNCED",
                'user' => $request->user()->id,
                'date_time' => $request->rep_date,
            ]);

            NewBounceCheck::where('id', $request->rep_bounce_id)->update(['status' => 'SETTLED CHECK']);

        });

        return redirect()->back();

    }

    public function bouncedCheckCashReplacement(CheckCashReplacementRequest $request)
    {
        $request->validated();

        if ($request->rep_check_date > today()->toDateString()) {
            $check_type = "POST DATED";
        } else {
            $check_type = "DATED CHECK";
        }

        DB::transaction(function () use ($request, $check_type) {

            Checks::where('checks_id', $request->rep_check_id)->update(['cash' => NumberHelper::float($request->rep_cash_amount)]);

            $new_check_id = Checks::create([
                'checksreceivingtransaction_id' => 0,
                'businessunit_id' => $request->user()->businessunit_id,
                'check_bounced_id' => $request->rep_check_id,
                'date_time' => today()->toDateString(),
                'check_type' => $check_type,
                'user' => $request->user()->id,
                'check_status' => 'CLEARED',
                'check_no' => $request->checkNumber,
                'bank_id' => $request->bank_id,
                'department_from' => $request->checkFrom_id,
                'currency_id' => $request->currency_id,
                'check_date' => $request->rep_check_date,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_class' => $request->checkClass,
                'check_category' => $request->category,
                'check_received' => $request->rep_check_received,
                'account_name' => $request->accountname,
                'account_no' => $request->accountnumber,
                'approving_officer' => $request->approvingOfficer,
                'customer_id' => $request->customerId,
                'is_exist' => 0,
                'is_manual_entry' => 0,
            ]);

            NewSavedChecks::create([
                'checks_id' => $new_check_id->checks_id,
                'check_type' => $request->rep_check_date,
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
            ]);

            NewCheckReplacement::create([
                'bounce_id' => $request->rep_bounce_id,
                'checks_id' => $request->rep_check_id,
                'cash' => 0,
                'rep_check_id' => $new_check_id->checks_id,
                'reason' => $request->rep_reason,
                'penalty' => NumberHelper::float($request->rep_check_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                'mode' => "CHECK & CASH",
                'status' => "BOUNCED",
                'user' => $request->user()->id,
                'date_time' => $request->rep_date,
            ]);

            NewBounceCheck::where('id', $request->rep_bounce_id)->update(['status' => 'SETTLED CHECK']);

        });
        return redirect()->back();

    }
    public function bounceCheckReDeposit(CheckReDepositRequest $request)
    {
        $request->validated();

        $is_exist = NewCheckReplacement::where('checks_id', $request->rep_check_id)->where('mode', 'RE-DEPOSIT')->exists();

        if ($is_exist) {
            return response()->json(['Exists' => true]);
        } else {
            DB::transaction(function () use ($request) {
                NewSavedChecks::where('checks_id', $request->rep_check_id)->update(['status' => '']);

                Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'CLEARED']);

                NewCheckReplacement::create([
                    'checks_id' => $request->rep_check_id,
                    'check_amount' => NumberHelper::float($request->rep_check_amount),
                    'mode' => 'RE-DEPOSIT',
                    'status' => 'BOUNCED',
                    'penalty' => $request->rep_penalty,
                    'ar_ds' => $request->rep_ar_ds,
                    'reason' => $request->rep_reason,
                    'user' => $request->user()->id,
                    'date_time' => $request->rep_date,
                    'bounce_id' => $request->rep_bounce_id,
                    'rep_check_id' => 0,
                    'check_amount_paid' => 0,
                    'cash' => 0,
                ]);

                NewDsChecks::create([
                    'checks_id' => $request->rep_check_id,
                    'ds_no' => $request->rep_ar_ds,
                    'date_deposit' => $request->rep_date,
                    'user' => $request->user()->id,
                    'date_time' => today()->toDateString(),
                    'status' => '',
                ]);
                NewBounceCheck::where('id', $request->rep_bounce_id)->update(['status' => 'SETTLED CHECK']);

            });
            return redirect()->back();
        }
    }
    public function bouncePartialPaymentCash(PartialPaymentCashRequest $request)
    {
        $request->validated();

        DB::transaction(function () use ($request) {
            NewCheckReplacement::create([
                'bounce_id' => $request->rep_bounce_id,
                'rep_check_id' => 0,
                'check_amount_paid' => 0,
                'checks_id' => $request->rep_check_id,
                'check_amount' => NumberHelper::float($request->rep_cash_amount),
                'cash' => NumberHelper::float($request->rep_cash_amount),
                'mode' => 'PARTIAL',
                'status' => 'BOUNCED',
                'penalty' => $request->rep_cash_penalty,
                'ar_ds' => $request->rep_ar_ds,
                'reason' => $request->rep_reason,
                'user' => Auth::user()->id,
                'date_time' => $request->rep_date,
            ]);

            Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'PARTIAL']);
            NewBounceCheck::where('id', $request->rep_bounce_id)->update(['status' => 'PARTIAL']);

        });

        return redirect()->back();

    }
    public function bouncePartialReplaymentCheck(PartialReplaymentCheckRequest $request)
    {
        $request->validated();
        $check_type = "";

        if ($request->rep_check_date > today()->toDateString()) {
            $check_type = "POST DATED";
        } else {
            $check_type = "DATED CHECK";

        }

        DB::transaction(function () use ($request, $check_type) {
            $new_check_id = Checks::create([
                'checksreceivingtransaction_id' => 0,
                'businessunit_id' => $request->user()->businessunit_id,
                'check_bounced_id' => $request->rep_check_id,
                'date_time' => today()->toDateString(),
                'check_type' => $check_type,
                'user' => $request->user()->id,
                'check_status' => 'CLEARED',
                'check_no' => $request->checkNumber,
                'bank_id' => $request->bank_id,
                'department_from' => $request->checkFrom_id,
                'currency_id' => $request->currency_id,
                'check_date' => $request->rep_check_date,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_class' => $request->checkClass,
                'check_category' => $request->category,
                'check_received' => $request->rep_check_received,
                'account_name' => $request->accountname,
                'account_no' => $request->accountnumber,
                'approving_officer' => $request->approvingOfficer,
                'customer_id' => $request->customerId,
                'is_exist' => 0,
                'is_manual_entry' => 0,
            ]);

            NewSavedChecks::create([
                'checks_id' => $new_check_id->checks_id,
                'check_type' => $request->rep_check_date,
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
            ]);

            NewCheckReplacement::create([
                'bounce_id' => $request->rep_bounce_id,
                'checks_id' => $request->rep_check_id,
                'cash' => 0,
                'rep_check_id' => $new_check_id->checks_id,
                'reason' => $request->rep_reason,
                'penalty' => NumberHelper::float($request->rep_check_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                'mode' => "PARTIAL",
                'status' => "BOUNCED",
                'user' => $request->user()->id,
                'date_time' => $request->rep_date,
            ]);

            Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'PARTIAL']);
            NewBounceCheck::where('id', $request->rep_bounce_id)->update(['status' => 'PARTIAL']);
        });

        return redirect()->back();

    }
    public function getCheckReplace(Request $request)
    {
        $q = NewCheckReplacement::joinCheckReplacementCustomer()
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('new_check_replacement.status', '!=', '')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where(function ($query) use ($request) {
                $query->where('checks.check_no', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('checks.check_amount', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('customers.fullname', 'like', '%' . $request->searchQuery . '%');
                return $query;
            })
            ->orderByDesc('new_check_replacement.id')
            ->select('*', 'new_check_replacement.date_time');

        $q = match ($request->getMode) {
            '1' => $q->where('mode', 'CHECK'),
            '2' => $q->where('mode', 'CHECK & CASH'),
            '3' => $q->where('mode', 'CASH'),
            '5' => $q->where('mode', 'PARTIAL'),
            '4' => $q->where('mode', 'RE-DEPOSIT'),
            default => $q
        };
        $data = $q->paginate(10)->withQueryString();

        return Inertia::render('Transaction/CheckReplace', [
            'data' => $data,
            'columns' => ColumnsHelper::$check_replace_columns,
            'getModeProps' => $request->getMode,
        ]);
    }

    public function replacementDetails(Request $request)
    {

        // dd($request->bouncedId);

        if ($request->bouncedId == 0) {
            $data = NewCheckReplacement::join('checks', 'checks.checks_id', 'new_check_replacement.checks_id')
                ->select('new_check_replacement.*', 'checks.approving_officer', )
                ->where('new_check_replacement.checks_id', $request->checksId)
                ->orderBy('id')
                ->first();

        } else {
            $data = NewCheckReplacement::join('checks', 'checks.checks_id', 'new_check_replacement.checks_id')
                ->select('new_check_replacement.*', 'checks.approving_officer')
                ->where('new_check_replacement.bounce_id', $request->bouncedId)
                ->orderBy('id')
                ->first();
        }

        $repCheckNo = Checks::where('checks_id', $data->rep_check_id)->first();

        $result = [];

        if ($data->mode == 'CASH') {
            $result = [
                'mode' => $data->mode,
                'check_amount' => number_format($data->check_amount, 2),
                'check_amount_paid' => number_format($data->cash, 2),
                'penalty' => number_format($data->penalty, 2),
                'reason' => $data->reason,
                'ar_ds' => $data->ar_ds,
                'replacement_id' => $data->id,
                'replacement_status' => $data->status,
                'approving_officer' => $data->approving_officer,
            ];
        } elseif ($data->mode == 'CHECK') {
            $result = [
                'mode' => $data->mode,
                'check_no' => $repCheckNo->check_no,
                'check_amount' => number_format($data->check_amount_paid, 2),
                'penalty' => number_format($data->penalty, 2),
                'reason' => $data->reason,
                'ar_ds' => $data->ar_ds,
                'replacement_id' => $data->id,
                'replacement_status' => $data->status,
                'rep_check_id' => $data->rep_check_id,
                'approving_officer' => $data->approving_officer,
            ];
        } elseif ($data->mode == 'CHECK & CASH') {
            $result = [
                'mode' => $data->mode,
                'check_no' => $repCheckNo->check_no,
                'check_amount' => number_format($data->check_amount_paid, 2),
                'cash' => number_format($data->cash, 2),
                'penalty' => number_format($data->penalty, 2),
                'reason' => $data->reason,
                'ar_ds' => $data->ar_ds,
                'replacement_id' => $data->id,
                'replacement_status' => $data->status,
                'rep_check_id' => $data->rep_check_id,
                'approving_officer' => $data->approving_officer,
            ];
        } elseif ($data->mode == 'PARTIAL') {
            $result = [
                'mode' => $data->mode,
                'checks_id' => $request->checksId,
                'bounce_id' => $request->bouncedId,
            ];
        } elseif ($data->mode == 'RE-DEPOSIT') {
            $result = [
                'mode' => $data->mode,
                'penalty' => number_format($data->penalty, 2),
                'reason' => $data->reason,
                'ar_ds' => $data->ar_ds,
                'replacement_id' => $data->id,
                'replacement_status' => $data->status,
                'approving_officer' => $data->approving_officer,
            ];
        }

        return response()->json($result);

    }

    public function replacedPartialPaymentTable(Request $request)
    {

        $result = [];
        if ($request->bouncedId == 0) {
            $data = NewCheckReplacement::join('users', 'users.id', '=', 'new_check_replacement.user')
                ->where('new_check_replacement.checks_id', $request->checksId)->cursor();

            $result = [];
            $cash = 0;
            $c = 0;
            $balance = 0;
            $amount = 0;

            $data->each(function ($item) use (&$result, &$cash, &$c, &$balance, &$amount) {

                $balanced = 0;

                if ($c = 0) {
                    $amount = $item->check_amount;
                    $balance = $item->cash - $item->check_amount;
                    if ($item->check_amount_paid != 0) {
                        $balance = $item->check_amount - $item->check_amount_paid;
                    }
                } else {
                    $amount = $balanced;
                    $balance = $item->cash - $balance;
                    if ($item->check_amount_paid != 0) {
                        $balance = $item->check_amount_paid - $balance;
                    }
                }

                $balanced = $amount - $cash;

                $partialCheckNo = Checks::where('checks_id', $item->rep_check_id)->first();
                if ($partialCheckNo) {
                    $result[] = [
                        'date' => date('m-d-Y', strtotime($item->date_time)),
                        'balanced' => number_format($balanced, 2),
                        'cash' => number_format($item->cash, 2),
                        'check_paid' => number_format($item->check_amount_paid, 2),
                        'balance' => number_format($balance, 2),
                        'penalty' => number_format($item->penalty, 2),
                        'checkNumber' => $partialCheckNo->check_no,
                        'partial_check_amount' => NumberHelper::float($partialCheckNo->check_amount),
                        'ar_ds' => $item->ar_ds,
                        'name' => $item->name,
                    ];
                } else {
                    $result[] = [
                        'date' => date('m-d-Y', strtotime($item->date_time)),
                        'balanced' => number_format($balanced, 2),
                        'cash' => number_format($item->cash, 2),
                        'check_paid' => number_format($item->check_amount_paid, 2),
                        'balance' => number_format($balance, 2),
                        'penalty' => number_format($item->penalty, 2),
                        'checkNumber' => 'N/A',
                        'ar_ds' => $item->ar_ds,
                        'name' => $item->name,
                    ];

                }
                $cash = $item->cash;
                if ($item->check_amount_paid != 0) {
                    $cash = $item->check_amount_paid;
                }
                $c++;
            });

        } else {

            $data = NewCheckReplacement::join('users', 'users.id', '=', 'new_check_replacement.user')
                ->where('new_check_replacement.bounce_id', $request->bouncedId)->cursor();

            $result = [];
            $cash = 0;
            $c = 0;
            $balance = 0;
            $amount = 0;

            $data->each(function ($item) use (&$result, &$cash, &$c, &$balance, &$amount) {
                $balanced = 0;

                if ($c = 0) {
                    $amount = $item->check_amount;
                    $balance = $item->cash - $item->check_amount;
                    if ($item->check_amount_paid != 0) {
                        $balance = $item->check_amount - $item->check_amount_paid;
                    }
                } else {
                    $amount = $balanced;
                    $balance = $item->cash - $balance;
                    if ($item->check_amount_paid != 0) {
                        $balance = $item->check_amount_paid - $balance;
                    }
                }

                $balanced = $amount - $cash;

                $partialCheckNo = Checks::where('checks_id', $item->rep_check_id)->first();
                if ($partialCheckNo) {
                    $result[] = [
                        'date' => date('m-d-Y', strtotime($item->date_time)),
                        'balanced' => number_format($balanced, 2),
                        'cash' => number_format($item->cash, 2),
                        'check_paid' => number_format($item->check_amount_paid, 2),
                        'balance' => number_format($balance, 2),
                        'penalty' => number_format($item->penalty, 2),
                        'checkNumber' => $partialCheckNo->check_no . ' [ Check amount ] : ' . NumberHelper::float($partialCheckNo->check_amount),
                        'ar_ds' => $item->ar_ds,
                        'name' => $item->name,

                    ];
                } else {
                    $result[] = [
                        'date' => date('m-d-Y', strtotime($item->date_time)),
                        'balanced' => number_format($balanced, 2),
                        'cash' => number_format($item->cash, 2),
                        'check_paid' => number_format($item->check_amount_paid, 2),
                        'balance' => number_format($balance, 2),
                        'penalty' => number_format($item->penalty, 2),
                        'checkNumber' => 'N/A',
                        'ar_ds' => $item->ar_ds,
                        'name' => $item->name,

                    ];

                }
                $cash = $item->cash;
                if ($item->check_amount_paid != 0) {
                    $cash = $item->check_amount_paid;
                }
                $c++;
            });

        }
        // dd($result);
        return response()->json($result);
    }

    public function getPartialPayment(Request $request)
    {

        $currency = Currency::orderBy('currency_name')->get();
        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();
        $check_class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

        $data = NewCheckReplacement::joinCheckReplacementCustomer()
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->where('checks.check_status', 'PARTIAL')
            ->where('new_check_replacement.mode', 'PARTIAL')
            ->select('checks.*', 'customers.*', 'new_check_replacement.status', 'new_check_replacement.*', 'banks.bankbranchname', 'department.department')
            ->where(function ($query) use ($request) {
                $query->where('checks.check_no', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('checks.check_amount', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('customers.fullname', 'like', '%' . $request->searchQuery . '%');
                return $query;
            })
        // ->groupBy('new_check_replacement.checks_id')
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) {
            $check_replacement = NewCheckReplacement::checksMode($value->checks_id)
                ->selectRaw('SUM(new_check_replacement.cash) as paid_cash, SUM(new_check_replacement.check_amount_paid) as paid_check')
                ->first();

            $bounceDate = '';

            if ($value->bounce_id != 0) {
                $bounceDateRecord = NewBounceCheck::where('id', $value->bounce_id)->first();
                $bounceDate = Date::parse($bounceDateRecord->date_time ?? '')->toFormattedDateString();
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
        // dd(1);

        return Inertia::render('Transaction/PartialPayments', [
            'data' => $data,
            'columns' => ColumnsHelper::$partial_payment_columns,
            'currency' => $currency,
            'category' => $category,
            'check_class' => $check_class,

        ]);
    }

    public function partialPaymentTableNotNull(Request $request)
    {
        // dd($request->all());
        $partialsData = NewCheckReplacement::join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks_id', $request->checksId)
            ->where('mode', "PARTIAL")
            ->cursor();

        $bounceData = NewBounceCheck::where('id', $request->bouncedId)->first();

        $bounceDate = strtotime($bounceData->date_time);
        $today = strtotime(now()->toDateString());

        $seconds = $today - $bounceDate;
        $days = $seconds / 86400;

        $result = [];
        $total = 0;
        $checkAmount = 0;
        $count = 1;

        $partialsData->each(function ($value) use (&$total, &$result, &$checkAmount, &$count) {
            $result[] = [
                'count' => $count++ . ".",
                'cash' => number_format($value->cash, 2),
                'cashAmountPaid' => number_format($value->check_amount_paid, 2),
                'penalty' => number_format($value->penalty, 2),
                'arDs' => $value->ar_ds,
                'reason' => $value->reason,
                'name' => $value->name,
                'dateTime' => Date::parse($value->date_time)->toFormattedDateString(),
            ];
            $total += $value->cash;
            $total += $value->check_amount_paid;
            $checkAmount = $value->check_amount;
        });
        $grandTotal = $checkAmount - $total;

        return response()->json([
            'data' => $result,
            'grandTotal' => NumberHelper::float($grandTotal),
            'days' => $days,
        ]);

    }

    public function partialPaymentTableNull(Request $request)
    {
        // dd($request->all());

        $partialsData = NewCheckReplacement::join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('checks_id', $request->checksId)
            ->where('mode', "PARTIAL")
            ->cursor();

        $checkData = Checks::where('checks_id', $request->checksId)
            ->first();

        $checkDate = strtotime($checkData->check_date);
        $today = strtotime(now()->toDateString());

        $seconds = $today - $checkDate;
        $days = $seconds / 86400;

        $result = [];

        $total = 0;
        $checkAmount = 0;
        $count = 1;

        $partialsData->each(function ($value) use (&$total, &$result, &$checkAmount, &$count) {
            $result[] = [
                'count' => $count++ . ".",
                'cash' => number_format($value->cash, 2),
                'cashAmountPaid' => number_format($value->check_amount_paid, 2),
                'penalty' => number_format($value->penalty, 2),
                'arDs' => $value->ar_ds,
                'reason' => $value->reason,
                'name' => $value->name,
                'dateTime' => Date::parse($value->date_time)->toFormattedDateString(),
            ];
            $total += $value->cash;
            $total += $value->check_amount_paid;
            $checkAmount = $value->check_amount;
        });

        $grandTotal = $checkAmount - $total;

        return response()->json([
            'data' => $result,
            'grandTotal' => NumberHelper::float($grandTotal),
            'days' => $days,
        ]);
    }
    public function submitPartialPayment(PartialPaymentRequest $request)
    {
        $request->validated();
        DB::transaction(function () use ($request) {
            if ($request->bouncedId == 0) {
                $transactions = $request->checkAmount - $request->checkAmountBalance;
                if ($transactions == 0) {
                    Checks::where('checks_id', $request->checksId)->update(['check_status' => 'CASH']);
                }
                NewCheckReplacement::create([
                    'rep_check_id' => 0,
                    'checks_id' => $request->checksId,
                    'check_amount' => $request->checkAmount,
                    'cash' => $request->checkAmountBalance,
                    'penalty' => NumberHelper::float($request->parPenalty),
                    'ar_ds' => $request->parArDs,
                    'reason' => $request->parReason,
                    'mode' => "PARTIAL",
                    'user' => Auth::user()->id,
                    'date_time' => $request->parRepDate,
                    'bounce_id' => 0,
                    'check_amount_paid' => 0,
                    'status' => '',
                ]);
            } else {
                $transactions = $request->checkAmount - $request->checkAmountBalance;
                if ($transactions == 0) {
                    Checks::where('checks_id', $request->checksId)->update(['check_status' => 'CASH']);
                    NewBounceCheck::where('id', $request->bounceId)->update(['status' => 'SETTLED CHECK']);
                }
                NewCheckReplacement::create([
                    'rep_check_id' => 0,
                    'check_amount_paid' => 0,
                    'checks_id' => $request->checksId,
                    'check_amount' => $request->checkAmount,
                    'cash' => $request->checkAmountBalance,
                    'penalty' => NumberHelper::float($request->parPenalty),
                    'ar_ds' => $request->parArDs,
                    'reason' => $request->parReason,
                    'mode' => "PARTIAL",
                    'user' => Auth::user()->id,
                    'date_time' => $request->parRepDate,
                    'bounce_id' => $request->bouncedId,
                    'status' => '',
                ]);
            }
        });
    }
    public function getBounceCheckType(Request $request)
    {

        if ($request->type == 1) {
            $data = NewBounceCheck::join('checks', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->where('new_bounce_check.status', '=', '')
                ->where('checks.businessunit_id', Auth::user()->businessunit_id)
                ->select(
                    'check_received',
                    'check_date',
                    'new_bounce_check.date_time',
                    'fullname',
                    'check_no',
                    'new_bounce_check.checks_id',
                    'check_amount',
                    'new_bounce_check.id',
                    'checks.account_name',
                    'checks.account_no'
                )->cursor();

            $result = [];

            $data->each(function ($value) use (&$result) {
                $result[] =
                    [
                    'checkCred' => $value->id,
                    'customer' => $value->fullname,
                    'amount' => $value->check_amount,
                ];
            });
        } else {
            $data = NewSavedChecks::join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
                ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
                ->where('check_date', '>', DB::raw('check_received'))
                ->where('new_saved_checks.status', "")
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('new_ds_checks')
                        ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
                })
                ->where('checks.businessunit_id', Auth::user()->businessunit_id)
                ->cursor();

            $result = [];

            $data->each(function ($value) use (&$result) {
                $result[] =
                    [
                    'checkCred' => $value->checks_id,
                    'customer' => $value->fullname,
                    'amount' => $value->check_amount,
                ];
            });
        }
        return response()->json($result);
    }

    public function submitPartialPaymentCheck(PartialPaymentCheckRequest $request)
    {
        $request->validated();

        if ($request->bouncedId == 0) {
            DB::transaction(function () use ($request) {

                $subTotal = $request->rep_check_amount - NumberHelper::float($request->checkAmount);
                if ($subTotal <= 0) {
                    Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'CHECK']);
                }
                $checkType = '';

                if ($request->rep_check_date > today()->toDateString()) {
                    $checkType = "POST DATED";
                } else {
                    $checkType = "DATED CHECK";
                }
                $data = Checks::create([
                    'is_exist' => 0,
                    'is_manual_entry' => 0,
                    'checksreceivingtransaction_id' => 0,
                    'businessunit_id' => $request->user()->businessunit_id,
                    'check_bounced_id' => $request->rep_check_id,
                    'check_type' => $checkType,
                    'check_status' => 'CLEARED',
                    'user' => $request->user()->id,
                    'date_time' => today()->toDateString(),

                    'check_no' => $request->checkNumber,
                    'customer_id' => $request->customerId,
                    'bank_id' => $request->bank_id,
                    'department_from' => $request->checkFrom_id,
                    'currency_id' => $request->currency_id,
                    'check_date' => $request->rep_check_date,
                    'check_amount' => $request->rep_check_amount,
                    'check_class' => $request->checkClass,
                    'check_category' => $request->category,
                    'check_received' => $request->rpe_check_received,
                    'account_no' => $request->accountnumber,
                    'account_name' => $request->accountname,
                    'approving_officer' => $request->approvingOfficer,

                ]);

                NewSavedChecks::create([
                    'checks_id' => $data->checks_id,
                    'check_type' => $request->rep_date,
                    'user' => $request->user()->id,
                    'date_time' => today()->toDateString(),
                    'status' => '',
                    'ds_status' => '',
                    'receive_status' => '',
                    'done' => '',
                ]);

                if (NumberHelper::float($request->checkAmount) <= $request->rep_check_amount) {
                    NewCheckReplacement::create([
                        'bounce_id' => 0,
                        'cash' => 0,
                        'status' => '',
                        'checks_id' => $request->rep_check_id,
                        'rep_check_id' => $data->checks_id,
                        'reason' => $request->rep_reason,
                        'penalty' => NumberHelper::float($request->rep_check_penalty),
                        'check_amount' => NumberHelper::float($request->parCheckAmount),
                        'check_amount_paid' => NumberHelper::float($request->checkAmount),
                        'mode' => "PARTIAL",
                        'user' => $request->user()->id,
                        'date_time' => $request->rep_date,
                    ]);
                } else {
                    $newCheckAmountPaid = $request->checkAmount - $request->rep_check_amount;

                    NewCheckReplacement::create([
                        'checks_id' => $request->rep_check_id,
                        'rep_check_id' => $data->checks_id,
                        'reason' => $request->rep_reason,
                        'penalty' => NumberHelper::float($request->rep_check_penalty),
                        'check_amount' => NumberHelper::float($request->parCheckAmount),
                        'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                        'mode' => "PARTIAL",
                        'user' => $request->user()->id,
                        'date_time' => $request->rep_date,
                        'bounce_id' => 0,
                        'cash' => 0,
                        'status' => '',
                    ]);

                    if ($request->conType == "1") {
                        $dataBounce = NewBounceCheck::where('id', $request->conCheckPartial)
                            ->join('checks', 'checks.checks_id', 'new_bounce_check.checks_id')
                            ->first();

                        NewCheckReplacement::create([
                            'checks_id' => $dataBounce->checks_id,
                            'rep_check_id' => $data->checks_id,
                            'reason' => $request->rep_reason,
                            'penalty' => NumberHelper::float($request->rep_check_penalty),
                            'check_amount' => NumberHelper::float($dataBounce->check_amount),
                            'check_amount_paid' => NumberHelper::float($newCheckAmountPaid),
                            'status' => "BOUNCED",
                            'mode' => "PARTIAL",
                            'user' => $request->user()->id,
                            'date_time' => $request->rep_date,
                            'bounce_id' => $request->conCheckPartial,
                            'cash' => 0,
                        ]);
                        Checks::where('checks_id', $dataBounce->checks_id)->update(['check_status' => 'PARTIAL']);
                        NewBounceCheck::where('id', $request->conCheckPartial)->update(['status' => 'PARTIAL']);
                    } else {
                        $conDetails = Checks::where('checks_id', $request->conCheckPartial)->first();

                        NewCheckReplacement::create([
                            'checks_id' => $request->conCheckPartial,
                            'rep_check_id' => $data->checks_id,
                            'reason' => $request->rep_reason,
                            'penalty' => NumberHelper::float($request->rep_check_penalty),
                            'check_amount' => NumberHelper::float($conDetails->check_amount),
                            'check_amount_paid' => NumberHelper::float($newCheckAmountPaid),
                            'status' => "REDEEMED",
                            'mode' => "PARTIAL",
                            'user' => $request->user()->id,
                            'date_time' => $request->rep_date,
                            'cash' => 0,
                            'bounce_id' => 0,
                        ]);
                        Checks::where('checks_id', $request->conCheckPartial)->update(['check_status' => 'PARTIAL']);
                        NewBounceCheck::where('id', $request->conCheckPartial)->update(['status' => 'PARTIAL']);
                    }

                }
            });
        } else {
            DB::transaction(function () use ($request) {
                $subTotal = $request->rep_check_amount - NumberHelper::float($request->checkAmount);
                if ($subTotal <= 0) {
                    Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'CHECK']);
                    NewBounceCheck::where('id', $request->bouncedId)->update(['status' => 'SETTLED CHECK']);
                }
                $checkType = '';

                if ($request->rep_check_date > today()->toDateString()) {
                    $checkType = "POST DATED";
                } else {
                    $checkType = "DATED CHECK";
                }

                $data = Checks::create([
                    'is_exist' => 0,
                    'is_manual_entry' => 0,
                    'checksreceivingtransaction_id' => 0,
                    'businessunit_id' => $request->user()->businessunit_id,
                    'check_bounced_id' => $request->rep_check_id,
                    'check_type' => $checkType,
                    'check_status' => 'CLEARED',
                    'user' => $request->user()->id,
                    'date_time' => today()->toDateString(),

                    'check_no' => $request->checkNumber,
                    'customer_id' => $request->customerId,
                    'bank_id' => $request->bank_id,
                    'department_from' => $request->checkFrom_id,
                    'currency_id' => $request->currency_id,
                    'check_date' => $request->rep_check_date,
                    'check_amount' => NumberHelper::float($request->rep_check_amount),
                    'check_class' => $request->checkClass,
                    'check_category' => $request->category,
                    'check_received' => $request->rpe_check_received,
                    'account_no' => $request->accountnumber,
                    'account_name' => $request->accountname,
                    'approving_officer' => $request->approvingOfficer,

                ]);

                NewSavedChecks::create([
                    'checks_id' => $data->checks_id,
                    'check_type' => $request->rep_date,
                    'user' => $request->user()->id,
                    'date_time' => today()->toDateString(),
                    'status' => '',
                    'ds_status' => '',
                    'receive_status' => '',
                    'done' => '',
                ]);

                if (NumberHelper::float($request->checkAmount) <= $request->rep_check_amount) {
                    NewCheckReplacement::create([
                        'bounce_id' => $request->bouncedId,
                        'cash' => 0,
                        'status' => '',
                        'checks_id' => $request->rep_check_id,
                        'rep_check_id' => $data->checks_id,
                        'reason' => $request->rep_reason,
                        'penalty' => NumberHelper::float($request->rep_check_penalty),
                        'check_amount' => NumberHelper::float($request->parCheckAmount),
                        'check_amount_paid' => NumberHelper::float($request->checkAmount),
                        'mode' => "PARTIAL",
                        'user' => $request->user()->id,
                        'date_time' => $request->rep_date,
                    ]);
                } else {
                    $newCheckAmountPaid = $request->checkAmount - $request->rep_check_amount;

                    NewCheckReplacement::create([
                        'checks_id' => $request->rep_check_id,
                        'rep_check_id' => $data->checks_id,
                        'reason' => $request->rep_reason,
                        'penalty' => NumberHelper::float($request->rep_check_penalty),
                        'check_amount' => NumberHelper::float($request->parCheckAmount),
                        'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                        'mode' => "PARTIAL",
                        'user' => $request->user()->id,
                        'date_time' => $request->rep_date,
                        'cash' => 0,
                        'status' => '',
                        'bounce_id' => $request->bouncedId,
                    ]);

                    if ($request->conType == "1") {
                        $dataBounce = NewBounceCheck::where('id', $request->conCheckPartial)
                            ->join('checks', 'checks.checks_id', 'new_bounce_check.checks_id')
                            ->first();

                        NewCheckReplacement::create([
                            'checks_id' => $dataBounce->checks_id,
                            'rep_check_id' => $data->checks_id,
                            'reason' => $request->rep_reason,
                            'penalty' => NumberHelper::float($request->rep_check_penalty),
                            'check_amount' => NumberHelper::float($dataBounce->check_amount),
                            'check_amount_paid' => NumberHelper::float($newCheckAmountPaid),
                            'status' => 'BOUNCED',
                            'mode' => 'PARTIAL',
                            'user' => $request->user()->id,
                            'date_time' => $request->rep_date,
                            'bounce_id' => $request->conCheckPartial,
                            'cash' => 0,
                        ]);
                        Checks::where('checks_id', $dataBounce->checks_id)->update(['check_status' => 'PARTIAL']);
                        NewBounceCheck::where('id', $request->conCheckPartial)->update(['status' => 'PARTIAL']);
                    } else {

                        $conDetails = Checks::where('checks_id', $request->conCheckPartial)->first();
                        NewCheckReplacement::create([
                            'checks_id' => $request->conCheckPartial,
                            'rep_check_id' => $data->checks_id,
                            'reason' => $request->rep_reason,
                            'penalty' => NumberHelper::float($request->rep_check_penalty),
                            'check_amount' => NumberHelper::float($conDetails->check_amount),
                            'check_amount_paid' => NumberHelper::float($newCheckAmountPaid),
                            'status' => "REDEEMED",
                            'mode' => "PARTIAL",
                            'user' => $request->user()->id,
                            'date_time' => $request->rep_date,
                            'cash' => 0,
                            'bounce_id' => 0,
                        ]);
                        Checks::where('checks_id', $request->conCheckPartial)->update(['check_status' => 'PARTIAL']);
                        NewBounceCheck::where('id', $request->conCheckPartial)->update(['status' => 'PARTIAL']);
                    }

                }

            });
        }

    }
    public function getDatedPdcReports(Request $request)
    {
        $data = NewSavedChecks::filter($request->only(['status']), $request->user()->businessunit_id)
            ->select('*', 'checks.check_type as check-type', 'new_saved_checks.check_type as new_check_type')
            ->where(function ($query) use ($request) {
                $query->where('checks.check_no', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('checks.check_amount', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('customers.fullname', 'like', '%' . $request->searchQuery . '%');
                return $query;
            })
            ->whereBetween('checks.check_received', [$request->date_from, $request->date_to])
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) use ($request) {
            $typeStatus = '';

            if ($value->new_check_type <= today()) {
                $typeStatus = 'DATED';
            } else {
                $typeStatus = 'POST DATED';
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
            'dateRangeValue' => (!empty($request->date_from) && !empty($request->date_to)) ? [$request->date_from, $request->date_to] : '',
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

        $data = NewSavedChecks::filterDPdcReports($dateRange, $request->user()->businessunit_id)
            ->where(function ($query) use ($request) {
                $query->where('checks.check_no', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('checks.check_amount', 'like', '%' . $request->searchQuery . '%')
                    ->orWhere('customers.fullname', 'like', '%' . $request->searchQuery . '%');
                return $query;
            })
            ->paginate(10)->withQueryString();

        return Inertia::render('Transaction/DuePostDatedCheckReport', [
            'data' => $data,
            'columns' => ColumnsHelper::$due_pdc_reports_columns,
            'dateRangeValue' => $dateRange[0] === null ? null : $dateRange,
            'buname' => $buname,
        ]);
    }
    public function generateExcelDuePdcReports(Request $request)
    {
        // dd(1);
        $buname = BusinessUnit::where('businessunit_id', $request->user()->businessunit_id)->first();
        $dateRange = [$request->date_from, $request->date_to];

        $data = NewSavedChecks::filterDPdcReports($dateRange, $request->user()->businessunit_id)->get();

        return (new TransactionService())
            ->record($data)
            ->writeResultDuePdc($dateRange, $buname);
    }
}
