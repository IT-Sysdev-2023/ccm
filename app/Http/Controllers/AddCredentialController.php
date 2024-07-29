<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Customer;
use App\Services\CredentialsServices;
use Illuminate\Http\Request;

class AddCredentialController extends Controller
{
    public function __construct(public CredentialsServices $credentials)
    {

    }

    public function addCustomer(Request $request)
    {

       $cusCode = $this->credentials->addCustomer();

       $create = Customer::create([
            'cus_code' => $cusCode,
            'fullname' => strtoupper($request->customer),
            'id' => $request->user()->id,
            'is_blacklist' => 0
        ]);

        return response()->json($create);
    }

    public function addBank(Request $request)
    {

       $data = $this->credentials->addBankName($request);

       return response()->json($data);
    }

    public function addClass(Request $request)
    {

       $data = $this->credentials->addCheckClass($request);

       return response()->json($data);
    }



}
