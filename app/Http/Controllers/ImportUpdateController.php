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
    public function instImportfunction()
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '-1');
        set_time_limit(3600);

        $data = AppSetting::where('app_key', 'app_institutionalcheck_ip')
            ->first();

        $textfile_ip = $data->app_value;

        $txtfile_ip_new = $textfile_ip . '\\New\\' . strtoupper(Auth::user()->businessunit->loc_code_atp) . '\\';

        $txtfile_ip_uploaded = $textfile_ip . '\\Uploaded\\' . strtoupper(Auth::user()->businessunit->loc_code_atp) . '\\';


        $user = 'public';
        $password = 'public';


        exec('net use \\\172.16.161.30\\Institutional /user:' . $user . ' ' . $password . ' /persistent:no');
        $files = scandir($txtfile_ip_new);

        $cnt = count($files) - 2;

        if ($cnt == 0) {
            echo json_encode([
                'status' => 'noupdate',
                'message' => 'There is no update this time.'
            ]);
            exit();
        }

        usleep(80000);


        echo json_encode([
            'status' => 'counting',
            'message' => $cnt
        ]);

        $ch = collect();

        $hasError = false;

        DB::beginTransaction();

        $checkRec = new CheckRecieved;

        $checkRec->checksreceivingtransaction_ctrlno = $this->getControlNumber();
        $checkRec->id = Auth::user()->id;
        $checkRec->company_id = Auth::user()->company_id;
        $checkRec->businessunit_id = Auth::user()->businessunit_id;
        $checkRec->save();

        usleep(30000);

        $recid = $checkRec->checksreceivingtransaction_id;

        $cntasc = 1;

        foreach ($files as $file) {
            $arr_f = [];
            if (strpos($file, '.txt') !== false || strpos($file, '.TXT') !== false) {
                $r_f = fopen($txtfile_ip_new . $file . '', 'r');

                while (!feof($r_f)) {
                    //usleep(80000);
                    $arr_f[] = trim(fgets($r_f));
                }
                fclose($r_f);
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

                ////////////////////////////////////////////////////////////// TRACE START HERE //////////////////////////////////////////////////////////////////////////

                $arr_f = array_filter($arr_f);
                $arr_f = array_values($arr_f);

                for ($i = 0; $i < count($arr_f); $i++) {
                    if ($i == 0) {
                        $arr = explode(",", $arr_f[$i]);
                        $customer = $arr[1];
                    }
                    if ($i == 1) {
                        $arr = explode(",", $arr_f[$i]);
                        $checkno = $arr[1];
                    }
                    if ($i == 2) {
                        $arr = explode(",", $arr_f[$i]);
                        $class = $arr[1];
                    }
                    if ($i == 3) {
                        $arr = explode(",", $arr_f[$i]);
                        $checkdate = $arr[1];
                    }
                    if ($i == 5) {
                        $arr = explode(",", $arr_f[$i]);
                        $category = strtoupper($arr[1]);
                    }
                    if ($i == 4) {
                        $arr = explode(",", $arr_f[$i]);
                        $checktype = $arr[1];
                    }
                    if ($i == 6) {
                        $arr = explode(",", $arr_f[$i]);
                        $expire = $arr[1];
                    }
                    if ($i == 7) {
                        $arr = explode(",", $arr_f[$i]);
                        $accountnumber = $arr[1];
                    }
                    if ($i == 8) {
                        $arr = explode(",", $arr_f[$i]);
                        $accountname = $arr[1];
                    }
                    if ($i == 9) {
                        $arr = explode(",", $arr_f[$i]);
                        $bank = $arr[1];
                    }
                    if ($i == 10) {
                        $arr = explode(",", $arr_f[$i]);
                        $checkamount = $arr[1];
                    }
                    if ($i == 11) {
                        $arr = explode(",", $arr_f[$i]);
                        $daterec = $arr[1];
                    }
                    if ($i == 12) {
                        $arr = explode(",", $arr_f[$i]);
                        // dd($arr);
                        $approving_officer = $arr[1];
                    }

                }

                if (trim(str_replace('/', '', $expire)) == '' || trim($expire) == '') {
                    $expire = NULL;
                } else {
                    $cdarr = explode("/", $expire);
                    $expire = trim($cdarr[2]) . '-' . trim($cdarr[0]) . '-' . trim($cdarr[1]);
                }

                if (str_replace('/', '', $daterec) == '' || trim($daterec) == '') {
                    $daterec = NULL;
                } else {
                    $cdarr = explode("/", $daterec);
                    $daterec = trim($cdarr[2]) . '-' . trim($cdarr[0]) . '-' . trim($cdarr[1]);
                }

                // if (empty($daterec) || strpos($daterec, '/') === false) {
                //         $daterec = NULL;
                //     } else {
                //         $cdarr = explode("/", $daterec);
                //         if (count($cdarr) === 3) {
                //             $daterec = trim($cdarr[2]) . '-' . trim($cdarr[0]) . '-' . trim($cdarr[1]);
                //         } else {
                //             // Handle the case when the date format is not as expected.
                //             // You can set a default value, show an error message, or take another appropriate action.
                //             // Example:
                //             $daterec = 'Invalid Format';
                //         }
                //     }


                $cdarr = explode("/", $checkdate);
                //Y-M-D
                //03/25/2019
                $checkdate = trim($cdarr[2]) . '-' . trim($cdarr[0]) . '-' . trim($cdarr[1]);
                $customerid = "";

                //check if customer exist / create customer
                if ($id = $this->isCustomerNameExist($customer)) {
                    $customerid = $id;
                } else {
                    $customerid = $this->autoCreateCustomer($customer);
                }

                //check if bank exist / create bank
                $bankid = "";
                $bankname = trim($bank);
                if ($bid = $this->isBankExist($bankname)) {
                    $bankid = $bid;
                } else {
                    $bankid = $this->autoCreateNewBank($bankname);
                }


                $ch->push([
                    'customer' => $customer,
                    'checkno' => $checkno,
                    'class' => $class,
                    'category' => $category,
                    'expire' => $expire,
                    'checkreceived' => $daterec,
                    'checktype' => $checktype,
                    'accountname' => $accountname,
                    'accountnumber' => $accountnumber,
                    'bank' => $bank,
                    'checkamount' => $checkamount,
                    'checkdate' => $checkdate,
                    'approving_officer' => $approving_officer,
                ]);



                $check = new Checks();

                $check->checksreceivingtransaction_id = $recid;
                $check->customer_id = $customerid;
                $check->check_no = trim($checkno);
                $check->check_class = trim($class);
                $check->check_date = trim($checkdate);
                $check->check_received = $daterec;
                $check->check_type = trim(strtoupper($checktype));
                $check->account_no = trim($accountnumber);
                $check->account_name = trim($accountname);
                $check->bank_id = $bankid;
                $check->businessunit_id = Auth::user()->businessunit_id;
                $check->check_amount = trim(str_replace(',', '', $checkamount));
                $check->check_expiry = $expire;
                $check->check_category = trim($category);
                $check->check_status = 'PENDING';
                $check->department_from = 14;
                $check->currency_id = 1;
                $check->user = Auth::user()->id;
                $check->date_time = date('Y-m-d');
                $check->approving_officer = $approving_officer;


                if (!$check->save()) {
                    $hasError = true;
                    break;
                }

                echo json_encode([
                    'status' => 'saving',
                    'message' => 'Importing ' . $cntasc . ' of ' . $cnt,
                    'count' => $cntasc,
                    'max' => $cnt,

                ]);
                usleep(80000);
                $cntasc++;
            }
        }

        if ($hasError) {
            DB::rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Something Went Wrong.'
            ]);

            exit();
        } else {
            foreach ($ch as $c) {
                // echo $txtfile_ip_new.trim($c['checkno']).'.TXT';
                // if(file_exists($txtfile_ip_new.trim($c['checkno']).'.TXT')){
                //     echo 'yeah';
                // }
                // else{
                //     echo 'nah';
                // }
                // die();
                $move = File::move($txtfile_ip_new . trim($c['checkno']) . '.TXT', $txtfile_ip_uploaded . trim($c['checkno']) . '.TXT');
            }

            DB::commit();

            usleep(80000);

            echo json_encode([
                'status' => 'complete',
                'message' => 'Database Successfully Updated.'
            ]);
        }



        // dd($data);

    }
}
