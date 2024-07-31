<?php

namespace App\Services;

use App\Helper\NumberHelper;
use App\Models\Checks;
use App\Models\NewSavedChecks;
use Illuminate\Support\Facades\DB;

class TransactionDbServices
{
    public function setCheckManualEntryStore($request, $type, $bankId, $custId, $fromId)
    {
      return DB::transaction(function () use ($request, $type, $bankId, $custId, $fromId) {
            $data = Checks::create([
                'businessunit_id' => $request->user()->businessunit_id,
                'check_type' => $type,
                'check_status' => "CLEARED",
                'user' => $request->user()->id,
                'date_time' => today()->toDateString(),
                'check_no' => $request->checknumber,
                'customer_id' =>  $custId,
                'bank_id' => $bankId,
                'department_from' => $fromId,
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
                'status' => '',
                'ds_status' => '',
                'receive_status' => '',
                'done' => '',
            ]);
        });
    }
}
