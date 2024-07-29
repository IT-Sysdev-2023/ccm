<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Customer;
use Illuminate\Http\Request;

class AddCredentialController extends Controller
{
    //
    public function addCustomer(Request $request)
    {
       $cusCode = $this->generateCustomerCode();

       $create = Customer::create([
            'cus_code' => $cusCode,
            'fullname' => strtoupper($request->customer),
            'id' => $request->user()->id,
            'is_blacklist' => 0
        ]);

        return response()->json($create);

    }

    public function generateCustomerCode()
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
}
