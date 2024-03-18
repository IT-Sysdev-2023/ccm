<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Models\Checks;
use App\Models\NewCheckReplacement;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class DatedPdcChecksController extends Controller
{
    public function pdc_index(Request $request)
    {

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('checks.check_date', '>', 'checks.check_received')
            ->paginate(10);

        $data->transform(function ($value) {
            $value->check_received = Date::parse($value->check_received)->toFormattedDateString();
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return Inertia::render('Dated&PdcChecks/PDCChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$pdc_check_columns,
        ]);
    }
    public function dated_index(Request $request)
    {

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('check_date', '<=', 'check_received')
            ->paginate(10);

        $data->transform(function ($value) {
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return Inertia::render('Dated&PdcChecks/DatedChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$dated_check_columns

        ]);
    }
    public function pdc_cash_replacement(Request $request)
    {

        $request->validate([
            'rep_cash_penalty' => 'required|numeric',
            'rep_ar_ds' => 'required|string',
            'rep_reason' => 'required|string',
            'rep_date' => 'required|date'
        ], [
            'rep_cash_penalty.required' => 'The cash penalty field is required.',
            'rep_cash_penalty.numeric' => 'The cash penalty must be a number.',
            'rep_ar_ds.required' => 'The AR DS field is required.',
            'rep_ar_ds.string' => 'The AR DS must be a string.',
            'rep_reason.required' => 'The reason field is required.',
            'rep_reason.string' => 'The reason must be a string.',
            'rep_date.required' => 'The date field is required.',
            'rep_date.date' => 'The date must be a valid date format.'
        ]);


        $data = Checks::where('checks_id', $request->rep_check_id)->first();


        DB::transaction(function () use ($request, $data) {
            NewCheckReplacement::create([
                'bounce_id' => 0,
                'rep_check_id' => 0,
                'check_amount_paid' => 0,
                'checks_id' => $request->rep_check_id,
                'check_amount' => NumberHelper::float($data->check_amount),
                'cash' => NumberHelper::float($request->rep_cash_amount),
                'penalty' => NumberHelper::float($request->rep_cash_penalty),
                'ar_ds' => $request->rep_ar_ds,
                'reason' => $request->rep_reason,
                'mode' => "CASH",
                'status' => "REDEEMED",
                'user' => Auth::user()->id,
                'date_time' => $request->rep_date,
            ]);

            Checks::where('checks_id', $request->rep_check_id)->update(['check_status' => 'CASH', 'cash' => NumberHelper::float($request->rep_cash_amount)]);
            NewSavedChecks::where('checks_id', $request->rep_check_id)->update(['status' => 'REDEEM']);
        });

        return redirect()->back();
    }
}
