<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Department;
use Carbon\Carbon;

class CredentialsServices
{
    public function addCustomer()
    {
        $code = 0;

        $cman = Customer::orderByDesc('cus_code')->first();
        if ($cman != null) {
            $code = $cman->cus_code;
            return ++$code;
        } else {
            $data = AppSetting::where('app_key', 'app_customercode_start')->get();
            return $data[0]['app_value'];
        }
    }

    public function addBankName($request)
    {
        $bank = Bank::create([
            'bankcode' => 0,
            'bankbranchname' => strtoupper($request->bankName),
            'id' => $request->user()->id,
        ]);

        if ($bank) {
            return $bank;
        }
        return false;
    }

    public function addCheckClass($request)
    {
        $data = Department::create([
            'department' => $request->checkClass,
            'added_by' => $request->user()->id,
            'date_added' => now(),
            'date_modified' => now(),
            'modified_by' => 0,
            'buid' => 0,
        ]);
        return $data;
    }

}
