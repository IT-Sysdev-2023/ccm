<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AppSetting;
use App\Models\CheckRecieved;
use App\Models\Checks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportUpdateController extends Controller
{
    public function indeximportupdates()
    {
        $quoteApi = config('app.qoute_api');

        // dd($quoteApi);

        return Inertia::render('Updates&Import/InstitutionalUpdateChecks', [
            'qoute_api' => $quoteApi
        ]);
    }
    public function startImportChecks()
    {

        $directory = 'C:\Users\Programming\Documents\CCM_Textfile';

        $files = File::allFiles($directory);



        foreach ($files as $file) {
            // dd($file);
            $contents = File::get($file);
            $exp = explode("\n", $contents);

            $array = array_map(function ($element) {
                return str_replace("\r", "", $element);
            }, $exp);

            $customer = "";
            $checkno = "";
            $class = "";
            $category = "";
            $expire = "";
            $checkdate = "";
            $checkreceived = "";
            $checktype = "";
            $accountname = "";
            $accountnumber = "";
            $bank = "";
            $checkamount = "";
            $daterec = "";
            $approving_officer = "";

            foreach ($array as $key => $value) {

                $val_r = explode(",", $value);

                if ($key == 0) {
                    $customer = $val_r[1];
                }
                if ($key == 1) {
                    $checkno = $val_r[1];
                }
                if ($key == 2) {
                    $class = $val_r[1];
                }
                if ($key == 3) {
                    $checkdate = $val_r[1];
                }
                if ($key == 4) {
                    $checktype = $val_r[1];
                }
                if ($key == 5) {
                    $category = $val_r[1];
                }
                if ($key == 6) {
                    $expire = $val_r[1];
                }
                if ($key == 7) {
                    $accountnumber = $val_r[1];
                }
                if ($key == 8) {
                    $accountname = $val_r[1];
                }
                if ($key == 9) {
                    $bank = $val_r[1];

                }
                if ($key == 10) {
                    $checkamount = $val_r[1];

                }
                if ($key == 11) {
                    $daterec = $val_r[1];

                }
                if ($key == 12) {
                    $approving_officer = $val_r[1];
                }

            }


        }


    }
}
