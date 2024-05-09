<?php

namespace App\Traits;

use App\Models\AppSetting;
use App\Models\Bank;
use App\Models\BusinessUnit;
use App\Models\CheckRecieved;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
    public function importInstitutionalIpAddress()
    {
        $texFileIp = AppSetting::where('app_key', 'sample_inst_ip')->first()->app_value;

        $textFileIpInNewFolder = $texFileIp . '\\New\\' . strtoupper(Auth::user()->businessunit->loc_code_atp) . '\\';
        $textFileIpInUploadedFolder = $texFileIp . '\\Uploaded\\' . strtoupper(Auth::user()->businessunit->loc_code_atp) . '\\';
        $user = 'public';
        $password = 'public';

        exec('net use \\\172.16.161.30\\Institutional /user:' . $user . ' ' . $password . ' /persistent:no');

        $files = File::files($textFileIpInNewFolder);

        $tfCounts = count($files);

        return (object) [
            'textFileIpInUploadedFolder' => $textFileIpInUploadedFolder,
            'textFileIpInNewFolder' => $textFileIpInNewFolder,
            'tfCounts' => $tfCounts,
            'files' => $files,
        ];
    }

    public function checkEncashData()
    {
        $bunitEncashStart = BusinessUnit::where('businessunit_id', Auth::user()->businessunit_id)
            ->whereNotNull('b_encashstart')->first()->b_encashstart;

        $checkEnCash = DB::connection('sqlsrv')
            ->table('vip_dtl')
            ->join('vip_mst', 'vip_mst.encash_id', '=', 'vip_dtl.encash_id')
            ->join('customer', 'customer.custid', '=', 'vip_mst.custid')
            ->where('loc_code', Auth::user()->businessunit->loc_code_atp)
            ->where('vip_dtl.entry_no', '>', $this->getEncashLastEntryNo())
            ->where('vip_mst.encash_date', '>=', $bunitEncashStart)
            ->orderBy('entry_no', 'asc')
            ->select(
                'vip_dtl.*',
                'vip_mst.*',
                'customer.clastname',
                'customer.cfirstname',
                'customer.cmiddname',
                'customer.extension'
            )
            ->get();

        return $checkEnCash;
    }

    public function getEncashLastEntryNo()
    {
        $encashno = DB::table('checksreceivingtransaction')
            ->join('businessunit', 'businessunit.businessunit_id', '=', 'checksreceivingtransaction.businessunit_id')
            ->where('businessunit.loc_code_atp', Auth::user()->businessunit->loc_code_atp)
            ->whereNotNull('encash_entrynum')
            ->select('encash_entrynum')
            ->orderBy('checksreceivingtransaction.checksreceivingtransaction_id', 'desc')
            ->first();

        if (is_null($encashno)) {
            return 0;
        }
        return $encashno->encash_entrynum;

    }

}
