<?php

namespace App\Traits;

use App\Models\AppSetting;
use App\Models\Bank;
use App\Models\CheckRecieved;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

trait ImportUpateTraits
{
    //
    public function isCustomerNameExist($customername)
    {
        $cus = Customer::where('fullname', $customername)->first();
        if ($cus != null) {
            return $cus->customer_id;
        }
        return false;
    }
    public function generateCustomerCode()
    {
        $code = 0;
        $cman = Customer::limit(1)
            ->orderBy('cus_code', 'desc')
            ->first();
        //.dd($cman);
        if ($cman != null) {
            $code = $cman->cus_code;
            return ++$code;
        } else {
            echo 'deri';
            $data = AppSetting::where('app_key', 'app_customercode_start')
                ->get();
            return $data[0]['app_value'];
        }
    }
    public function autoCreateCustomer($customername)
    {
        $cuscode = $this->generateCustomerCode();
        $customer = new Customer;

        $customer->cus_code = $cuscode;
        $customer->fullname = strtoupper($customername);
        $customer->id = Auth::user()->id;
        $customer->is_blacklist = 0;

        if ($customer->save()) {
            return $customer->customer_id;
        }
        return false;
    }
    public function isBankExist($bankname)
    {
        $bank = Bank::where('bankbranchname', $bankname)->first();

        if ($bank != null) {
            return $bank->bank_id;
        }
        return false;
    }

    public function autoCreateNewBank($bankname)
    {
        $bank = new Bank;

        $bank->bankcode = "";
        $bank->bankbranchname = strtoupper($bankname);
        $bank->id = Auth::user()->id;

        if ($bank->save()) {
            return $bank->bank_id;
        }
        return false;
    }
    public function getControlNumber()
    {
        ini_set('memory_limit', '-1');
        $code = 0;
        $ctrlno = CheckRecieved::limit(1)
            ->where('company_id', Auth::user()->company_id)
            ->where('businessunit_id', Auth::user()->businessunit_id)
            ->orderBy('checksreceivingtransaction_ctrlno', 'desc')
            ->get();

        $cnt = count($ctrlno);

        if ($cnt < 0) {
            $code = $ctrlno[0]['checksreceivingtransaction_ctrlno'];
            return ++$code;

        } else {

            $data = AppSetting::where('app_key', 'app_check_controlno_start')
                ->get();
            return $data[0]['app_value'];
        }


    }

}
