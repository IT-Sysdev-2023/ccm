<?php

namespace App\Services;

use App\Helper\NumberHelper;
use App\Models\Checks;
use App\Models\NewCheckReplacement;
use App\Models\NewSavedChecks;
use Illuminate\Support\Facades\DB;

class DatedPdcDBServices
{
    public function pdcCashReplacement($request, $oldAmount)
    {
        DB::transaction(function () use ($request, $oldAmount) {
            NewCheckReplacement::create([
                'bounce_id' => 0,
                'rep_check_id' => 0,
                'check_amount_paid' => 0,
                'checks_id' => $request->id,
                'check_amount' => NumberHelper::float($oldAmount),
                'cash' => NumberHelper::float($request->amount),
                'penalty' => $request->penalty,
                'ar_ds' => $request->ards,
                'reason' => $request->reason,
                'mode' => "CASH",
                'status' => "REDEEMED",
                'user' => $request->user()->id,
                'date_time' => $request->date,
            ]);

            Checks::where('checks_id', $request->id)->update(['check_status' => 'CASH', 'cash' => NumberHelper::float($request->amount)]);
            NewSavedChecks::where('checks_id', $request->id)->update(['status' => 'REDEEM']);
        });
    }

    public function pdcCheckReplacement($request, $type, $custId, $bankId, $classId)
    {
        DB::transaction(function () use ($request, $type, $custId, $bankId , $classId) {
            $data = Checks::create([
                'checksreceivingtransaction_id' => 0,
                'businessunit_id' => $request->user()->businessunit_id,
                'check_bounced_id' => $request->id,
                'date_time' => today()->toDateString(),
                'check_type' => $type,
                'user' => $request->user()->id,
                'check_status' => 'CLEARED',
                'check_no' => $request->checkNo,
                'bank_id' => $bankId,
                'department_from' => $request->checkFrom,
                'currency_id' => $request->currency,
                'check_date' => $request->checkDate,
                'check_amount' => NumberHelper::float($request->amount),
                'check_class' => $classId,
                'check_category' => $request->checkCat,
                'check_received' => $request->checkRec,
                'account_name' => $request->accName,
                'account_no' => $request->account,
                'approving_officer' => $request->appOfficer,
                'customer_id' => $custId,
                'is_exist' => 0,
                'is_manual_entry' => 0,
            ]);

            NewSavedChecks::create([
                'checks_id' => $data->checks_id,
                'check_type' => $request->checkDate,
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
            ]);

            NewCheckReplacement::create([
                'bounce_id' => 0,
                'checks_id' => $request->id,
                'cash' => 0,
                'rep_check_id' => $data->checks_id,
                'reason' => $request->reason,
                'penalty' => NumberHelper::float($request->penAmount),
                'ar_ds' => $request->rep_ar_ds,
                'check_amount' => NumberHelper::float($request->amount),
                'check_amount_paid' => NumberHelper::float($request->amount),
                'mode' => "CHECK",
                'status' => "REDEEMED",
                'user' => $request->user()->id,
                'date_time' => $request->repDate,
            ]);

            NewSavedChecks::where('checks_id', $request->id)->update(['status' => 'REDEEM']);
        });
    }
}
