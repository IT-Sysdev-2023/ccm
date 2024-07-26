<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Http\Requests\CashReplacementRequest;
use App\Models\Checks;
use App\Models\Currency;
use App\Models\NewCheckReplacement;
use App\Models\NewSavedChecks;
use App\Services\DatedPdcCheckServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;


class DatedPdcChecksController extends Controller
{
    public function __construct(public DatedPdcCheckServices $datedPdcChecks)
    {
    }
    public function pdc_index(Request $request)
    {
        return inertia('Dated&PdcChecks/PDCChecks', [
            'data' => $this->datedPdcChecks->getPostDatedChecks($request),
            'filters' => $request->only(['search'])
        ]);
    }

    public function dated_index(Request $request)
    {
        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->selectFilterDated()
            ->whereColumn('check_date', '<=', 'check_received')
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) {
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return inertia('Dated&PdcChecks/DatedChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$dated_check_columns,
            'filters' => $request->only(['search'])
        ]);
    }

    public function pdc_cash_replacement(CashReplacementRequest $request)
    {
        // dd($request->all());
        $request->validated();

        $data = Checks::where('checks_id', $request->id)->first();

        DB::transaction(function () use ($request, $data) {
            NewCheckReplacement::create([
                'bounce_id' => 0,
                'rep_check_id' => 0,
                'check_amount_paid' => 0,
                'checks_id' => $request->id,
                'check_amount' => NumberHelper::float($data->check_amount),
                'cash' => NumberHelper::float($request->amount),
                'penalty' => $request->penalty,
                'ar_ds' => $request->ards,
                'reason' => $request->reason,
                'mode' => "CASH",
                'status' => "REDEEMED",
                'user' => Auth::user()->id,
                'date_time' => $request->date,
            ]);

            Checks::where('checks_id', $request->id)->update(['check_status' => 'CASH', 'cash' => NumberHelper::float($request->amount)]);
            NewSavedChecks::where('checks_id', $request->id)->update(['status' => 'REDEEM']);
        });
    }

    public function pdc_check_replacement(Request $request)
    {

        $request->validate(
            [
                'checkFrom_id' => 'required',
                'bank_id' => 'required',
                'customerId' => 'required',
                'approvingOfficer' => 'required',
                'currency_id' => 'required',
                'category' => 'required',
                'rep_reason' => 'required',
                'checkClass' => 'required',
                'rep_date' => 'required|date',
                'rep_check_date' => 'required|date',
                'rep_check_received' => 'required|date',
                'rep_check_penalty' => 'required',
                'accountname' => 'required',
                'accountnumber' => 'required',
                'checkNumber' => 'required',
            ],
            [
                'checkFrom_id.required' => 'The check from field is required.',
                'bank_id.required' => 'The bank name field is required.',
                'customerId.required' => 'The customer name field is required.',
                'approvingOfficer.required' => 'The approving officer field is required.',
                'currency_id.required' => 'The currency field is required.',
                'category.required' => 'The category field is required.',
                'rep_reason.required' => 'The reason field is required.',
                'checkClass.required' => 'The check class field is required.',
                'rep_date.required' => 'The replace date field is required.',
                'rep_date.date' => 'The replace date field is required.',
                'rep_check_date.required' => 'The check date field is required.',
                'rep_check_date.date' => 'The check date field must be a valid date format.',
                'rep_check_received.required' => 'The check recieved field is required.',
                'rep_check_received.date' => 'The check recieved field must be a valid date format.',
                'rep_check_penalty.required' => 'The penalty field is required.',
                'rep_check_penalty.numeric' => 'The penalty field must be a number.',
                'accountname.required' => 'The account name field is required.',
                'accountnumber.required' => 'The account number field is required.',
                'checkNumber.required' => 'The check number field is required.',
            ]
        );
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
                'bounce_id' => 0,
                'checks_id' => $request->rep_check_id,
                'cash' => 0,
                'rep_check_id' => $new_check_id->checks_id,
                'reason' => $request->rep_reason,
                'penalty' => NumberHelper::float($request->rep_check_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                'mode' => "CHECK",
                'status' => "REDEEMED",
                'user' => $request->user()->id,
                'date_time' => $request->rep_date,
            ]);

            NewSavedChecks::where('checks_id', $request->rep_check_id)->update(['status' => 'REDEEM']);
        });

        return redirect()->back();
    }

    public function pdc_check_cash_replacement(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'checkFrom_id' => 'required',
                'bank_id' => 'required',
                'customerId' => 'required',
                'approvingOfficer' => 'required',
                'currency_id' => 'required',
                'category' => 'required',
                'rep_reason' => 'required',
                'checkClass' => 'required',
                'rep_date' => 'required|date',
                'rep_check_date' => 'required|date',
                'rep_check_received' => 'required|date',
                'rep_check_penalty' => 'required',
                'accountname' => 'required',
                'accountnumber' => 'required',
                'checkNumber' => 'required',
                'rep_ar_ds' => 'required',
                'rep_cash_amount' => 'required|numeric',
            ],
            [
                'checkFrom_id.required' => 'The check from field is required.',
                'bank_id.required' => 'The bank name field is required.',
                'customerId.required' => 'The customer name field is required.',
                'approvingOfficer.required' => 'The approving officer field is required.',
                'currency_id.required' => 'The currency field is required.',
                'category.required' => 'The category field is required.',
                'rep_reason.required' => 'The reason field is required.',
                'checkClass.required' => 'The check class field is required.',
                'rep_date.required' => 'The replace date field is required.',
                'rep_date.date' => 'The replace date field is required.',
                'rep_check_date.required' => 'The check date field is required.',
                'rep_check_date.date' => 'The check date field must be a valid date format.',
                'rep_check_received.required' => 'The check recieved field is required.',
                'rep_check_received.date' => 'The check recieved field must be a valid date format.',
                'rep_check_penalty.required' => 'The penalty field is required.',
                'rep_check_penalty.numeric' => 'The penalty field must be a number.',
                'accountname.required' => 'The account name field is required.',
                'accountnumber.required' => 'The account number field is required.',
                'checkNumber.required' => 'The check number field is required.',
                'rep_ar_ds.required' => 'The Ar and Ds number field is required.',
                'rep_cash_amount.numeric' => 'The cash amount field is required.',
                'rep_cash_amount.required' => 'The cash amount  field is required.',
            ]
        );

        $check_type = "";

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
                'bounce_id' => 0,
                'checks_id' => $request->rep_check_id,
                'cash' => 0,
                'rep_check_id' => $new_check_id->checks_id,
                'reason' => $request->rep_reason,
                'penalty' => NumberHelper::float($request->rep_check_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                'mode' => "CHECK & CASH",
                'status' => "REDEEMED",
                'user' => $request->user()->id,
                'date_time' => $request->rep_date,
            ]);

            NewSavedChecks::where('checks_id', $request->rep_check_id)->update(['status' => 'REDEEM']);
        });
    }

    public function pdc_partial_replacement_cash(Request $request)
    {

        $request->validate([
            'rep_cash_penalty' => 'required|numeric',
            'rep_ar_ds' => 'required|string',
            'rep_reason' => 'required|string',
            'rep_date' => 'required|date',
        ], [
            'rep_cash_penalty.required' => 'The cash penalty field is required.',
            'rep_cash_penalty.numeric' => 'The cash penalty must be a number.',
            'rep_ar_ds.required' => 'The AR DS field is required.',
            'rep_ar_ds.string' => 'The AR DS must be a string.',
            'rep_reason.required' => 'The reason field is required.',
            'rep_reason.string' => 'The reason must be a string.',
            'rep_date.required' => 'The date field is required.',
            'rep_date.date' => 'The date field is required.',
        ]);

        DB::transaction(function () use ($request) {
            NewCheckReplacement::create([
                'bounce_id' => 0,
                'rep_check_id' => 0,
                'check_amount_paid' => 0,
                'checks_id' => $request->rep_check_id,
                'check_amount' => NumberHelper::float($request->rep_cash_amount),
                'cash' => NumberHelper::float($request->rep_cash_amount),
                'mode' => 'PARTIAL',
                'status' => 'REDEEMED',
                'penalty' => $request->rep_cash_penalty,
                'ar_ds' => $request->rep_ar_ds,
                'reason' => $request->rep_reason,
                'user' => Auth::user()->id,
                'date_time' => $request->rep_date,
            ]);

            Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'PARTIAL']);
            NewSavedChecks::where('checks_id', $request->rep_check_id)->update(['status' => 'REDEEM']);
        });

        return redirect()->back();
    }
    public function pdc_partial_replacement_check(Request $request)
    {

        $request->validate(
            [
                'checkFrom_id' => 'required',
                'bank_id' => 'required',
                'customerId' => 'required',
                'approvingOfficer' => 'required',
                'currency_id' => 'required',
                'category' => 'required',
                'rep_reason' => 'required',
                'checkClass' => 'required',
                'rep_date' => 'required|date',
                'rep_check_date' => 'required|date',
                'rep_check_received' => 'required|date',
                'rep_check_penalty' => 'required',
                'accountname' => 'required',
                'accountnumber' => 'required',
                'checkNumber' => 'required',
            ],
            [
                'checkFrom_id.required' => 'The check from field is required.',
                'bank_id.required' => 'The bank name field is required.',
                'customerId.required' => 'The customer name field is required.',
                'approvingOfficer.required' => 'The approving officer field is required.',
                'currency_id.required' => 'The currency field is required.',
                'category.required' => 'The category field is required.',
                'rep_reason.required' => 'The reason field is required.',
                'checkClass.required' => 'The check class field is required.',
                'rep_date.required' => 'The replace date field is required.',
                'rep_date.date' => 'The replace date field is required.',
                'rep_check_date.required' => 'The check date field is required.',
                'rep_check_date.date' => 'The check date field must be a valid date format.',
                'rep_check_received.required' => 'The check recieved field is required.',
                'rep_check_received.date' => 'The check recieved field must be a valid date format.',
                'rep_check_penalty.required' => 'The penalty field is required.',
                'rep_check_penalty.numeric' => 'The penalty field must be a number.',
                'accountname.required' => 'The account name field is required.',
                'accountnumber.required' => 'The account number field is required.',
                'checkNumber.required' => 'The check number field is required.',
            ]
        );
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
                'bounce_id' => 0,
                'checks_id' => $request->rep_check_id,
                'cash' => 0,
                'rep_check_id' => $new_check_id->checks_id,
                'reason' => $request->rep_reason,
                'penalty' => NumberHelper::float($request->rep_check_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->rep_check_amount),
                'check_amount_paid' => NumberHelper::float($request->rep_check_amount),
                'mode' => "CHECK",
                'status' => "REDEEMED",
                'user' => $request->user()->id,
                'date_time' => $request->rep_date,
            ]);

            Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'PARTIAL']);
            NewSavedChecks::where('checks_id', $request->rep_check_id)->update(['status' => 'REDEEM']);
        });

        return redirect()->back();
    }
}
