<?php

namespace App\Http\Controllers;

use App\Events\ImportUpdateEvents;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AppSetting;
use App\Models\CheckRecieved;
use App\Models\Checks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Traits\ImportUpateTraits;

class ImportUpdateController extends Controller
{
    use ImportUpateTraits;
    public function indeximportupdates()
    {
        $quoteApi = config('app.qoute_api');
        $directory = 'C:\Users\Programming\Documents\CCM_Textfile\files';

        $files = File::allFiles($directory);


        return Inertia::render('Updates&Import/InstitutionalUpdateChecks', [
            'qoute_api' => $quoteApi,
            'data' => $files
        ]);
    }
    public function startImportChecks()
    {


        $directory = 'C:\Users\Programming\Documents\CCM_Textfile\files';

        $files = File::allFiles($directory);

        $tfCounts = count($files);


        $ch = collect() ?? nullOrEmptyString();

        $checkReceived = CheckRecieved::create([
            'checksreceivingtransaction_ctrlno' => $this->getControlNumber(),
            'id' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
            'businessunit_id' => Auth::user()->businessunit_id,
        ]);

        $recid = $checkReceived->checksreceivingtransaction_id;

        $dbtransaction = array();
        $counting = 1;

        ImportUpdateEvents::dispatch('Importing Textfile...', '0', $tfCounts, Auth::user());

        sleep(1);

        foreach ($files as $file) {
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
                    // dd($expire);
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



            $cleaned_expire = trim(str_replace('/', '', $expire));
            $cdateArray = explode("/", $checkdate);

            $checkdate = trim($cdateArray[2]) . '-' . trim($cdateArray[0]) . '-' . trim($cdateArray[1]);

            // Check this if the expire dont have a value date
            if (empty($cleaned_expire) || empty(trim($expire))) {
                $expire = NULL;
            } else {
                $expire = trim($cdateArray[2]) . '-' . trim($cdateArray[0]) . '-' . trim($cdateArray[1]);
            }

            // Check when the customer is existed and should be created when not exist

            $customerid = "";

            if ($id = $this->isCustomerNameExist($customer)) {
                $customerid = $id;
            } else {
                $customerid = $this->autoCreateCustomer($customer);

            }
            // Check the bank when exist else create

            $bankid = "";

            $bankname = trim($bank);

            if ($bId = $this->isBankExist($bankname)) {
                $bankid = $bId;
            } else {

                $bankid = $this->autoCreateNewBank($bankname);
            }

            $ch->push([
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


            $dbtransaction = DB::transaction(
                fn() =>
                Checks::create([
                    'checksreceivingtransaction_id' => $recid,
                    'customer_id' => $customerid,
                    'check_no' => trim($checkno),
                    'check_class' => trim($class),
                    'check_date' => trim($checkdate),
                    'check_received' => trim($daterec),
                    'check_type' => trim($checktype),
                    'account_no' => trim($accountnumber),
                    'account_name' => trim($accountname),
                    'bank_id' => $bankid,
                    'businessunit_id' => Auth::user()->businessunit_id,
                    'check_amount' => trim(str_replace(',', '', $checkamount)),
                    'check_expiry' => $expire,
                    'check_category' => trim($category),
                    'check_status' => 'PENDING',
                    'department_from' => 14,
                    'currency_id' => 1,
                    'check_bounced_id' => 0,
                    'is_exist' => 0,
                    'is_manual_entry' => 0,
                    'user' => Auth::user()->id,
                    'date_time' => today(),
                    'approving_officer' => $approving_officer,
                ])
            );

            ImportUpdateEvents::dispatch('Importing Textfile...', $counting++, $tfCounts, Auth::user());

        }

        if ($dbtransaction) {
            foreach ($ch as $c) {

                $sourceFilePath = 'C:\Users\Programming\Documents\CCM_Textfile\files\\' . trim($c['checkno']) . '.TXT'; // Fixing the variable interpolation
                $destinationFilePath = 'C:\Users\Programming\Documents\CCM_Textfile\uploaded\\' . trim($c['checkno']) . '.TXT';

                File::move($sourceFilePath, $destinationFilePath);
            }
        } else {

        }

        sleep(2);

        return Inertia::render('Components/ImportUpdatePartials/ImportUpdateResult');




    }
}
