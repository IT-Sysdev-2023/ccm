<?php

namespace App\Services;

use App\Events\ImportUpdateAtpEvent;
use App\Events\ImportUpdateAtpAllEvent;
use App\Events\ImportUpdateEvents;
use App\Models\BusinessUnit;
use App\Models\CheckRecieved;
use App\Models\Checks;
use App\Traits\ImportUpateTraits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ImportUpdateService
{
    use ImportUpateTraits;

    protected $record;
    public function __construct()
    {
    }
    function record($data): self
    {

        $this->record = $data;

        return $this;

    }
    public function importResult()
    {
        $ch = collect() ?? nullOrEmptyString();

        // $texFileIp = AppSetting::where('app_key', 'sample_inst_ip')->first()->app_value;

        // $textFileIpInNewFolder = $texFileIp . '\\New\\' . strtoupper(Auth::user()->businessunit->loc_code_atp) . '\\';
        // $textFileIpInUploadedFolder = $texFileIp . '\\Uploaded\\' . strtoupper(Auth::user()->businessunit->loc_code_atp) . '\\';
        // $user = 'public';
        // $password = 'public';

        // exec('net use \\\172.16.161.30\\Institutional /user:' . $user . ' ' . $password . ' /persistent:no');

        // $files = File::files($textFileIpInNewFolder);

        // $tfCounts = count($files);
        $textFileIpInUploadedFolder = $this->importInstitutionalIpAddress()->textFileIpInUploadedFolder;
        $textFileIpInNewFolder = $this->importInstitutionalIpAddress()->textFileIpInNewFolder;
        $files = $this->importInstitutionalIpAddress()->files;
        $tfCounts = $this->importInstitutionalIpAddress()->tfCounts;

        $checkReceived = CheckRecieved::create([
            'checksreceivingtransaction_ctrlno' => $this->getControlNumber(),
            'id' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
            'businessunit_id' => Auth::user()->businessunit_id,
        ]);

        $recid = $checkReceived->checksreceivingtransaction_id;

        $dbtransaction = array();
        $counting = 1;

        ImportUpdateEvents::dispatch('Importing Textfile...', 0, $tfCounts, Auth::user());

        sleep(1);

        foreach ($files as $file) {
            $contents = File::get($file);
            // dd($contents);
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
                $expire = null;
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

                $sourceFilePath = $textFileIpInNewFolder . trim($c['checkno']) . '.TXT'; // Fixing the variable interpolation
                $destinationFilePath = $textFileIpInUploadedFolder . trim($c['checkno']) . '.TXT';

                File::move($sourceFilePath, $destinationFilePath);
            }
        } else {

        }

        sleep(2);

        return Inertia::render('Components/ImportUpdatePartials/ImportUpdateResult');

    }

    public function updateResult()
    {
        

        $bunitAtpGetData = BusinessUnit::where('businessunit_id', Auth::user()->businessunit_id)->whereNotNull('b_atpgetdata')->get();

        $bunitEncashStart = BusinessUnit::where('businessunit_id', Auth::user()->businessunit_id)
            ->whereNotNull('b_encashstart')->first();

        $dateStartAtp = $bunitEncashStart->b_atpgetdata;
        $dateStartEncash = $bunitEncashStart->b_encashstart;

        $checksOnDatabase = Checks::where('businessunit_id', Auth::user()->businessunit_id)->count();
     

        $checkPostDatedCheck = [];
        $checkDatedCheck = [];

        if ($checksOnDatabase == 0) {
            try {
                $checkPostDatedCheck = DB::connection('sqlsrv')
                    ->table('chk_dtl')
                    ->join('chk_mst', 'chk_mst.issue_no', '=', 'chk_dtl.issue_no')
                    ->join('customer', 'customer.custid', '=', 'chk_mst.custid')
                    ->where('chk_mst.loc_code', Auth::user()->businessunit->loc_code_atp)
                    ->where('chk_dtl.chkdate', '>=', $dateStartAtp)
                    ->where('chk_mst.atp_date', '<', $dateStartAtp)
                    ->orderBy('entry_no', 'asc')
                    ->select(
                        'chk_mst.issue_no',
                        'chk_mst.atp_date',
                        'chk_dtl.entry_no',
                        'chk_dtl.chkclass',
                        'chk_dtl.chktype',
                        'chk_dtl.chkdate',
                        'chk_dtl.chkno',
                        'chk_dtl.bankname',
                        'chk_dtl.brstn_rtno',
                        'chk_dtl.actno',
                        'chk_dtl.actname',
                        'chk_dtl.chkamt',
                        'chk_dtl.chkexpiry',
                        'chk_dtl.category',
                        'chk_dtl.approvedby',
                        'customer.clastname',
                        'customer.cfirstname',
                        'customer.cmiddname',
                        'customer.extension'
                    )
                    ->get();

            } catch (\Exception $e) {
                exit();
            }
        }
            try {
                $checkDatedCheck = DB::connection('sqlsrv')
                    ->table('chk_dtl')
                    ->join('chk_mst', 'chk_mst.issue_no', '=', 'chk_dtl.issue_no')
                    ->join('customer', 'customer.custid', '=', 'chk_mst.custid')
                    ->where('chk_mst.loc_code', Auth::user()->businessunit->loc_code_atp)
                    ->where('chk_dtl.issue_no', '>', $this->getATPLastIssueNo())
                    ->where('chk_mst.atp_date', '>=', $dateStartAtp . ' 00:00:00')
                    ->orderBy('entry_no', 'asc')
                    ->select(
                        'chk_mst.issue_no',
                        'chk_mst.atp_date',
                        'chk_dtl.entry_no',
                        'chk_dtl.chkclass',
                        'chk_dtl.chktype',
                        'chk_dtl.chkdate',
                        'chk_dtl.chkno',
                        'chk_dtl.bankname',
                        'chk_dtl.brstn_rtno',
                        'chk_dtl.actno',
                        'chk_dtl.actname',
                        'chk_dtl.chkamt',
                        'chk_dtl.chkexpiry',
                        'chk_dtl.category',
                        'chk_dtl.approvedby',
                        'customer.clastname',
                        'customer.cfirstname',
                        'customer.cmiddname',
                        'customer.extension'
                    )
                    ->get();
            } catch (\Exception $e) {

                exit();
            }
        

        if ($checksOnDatabase == 0) {
            if (count($checkPostDatedCheck) > 0 && count($checkDatedCheck) > 0) {
                $checks = $checkDatedCheck->merge($checkPostDatedCheck);
            }
        } else {
            $checks = $checkDatedCheck;
        }

        try {
            $checkEnCash = DB::connection('sqlsrv')
                ->table('vip_dtl')
                ->join('vip_mst', 'vip_mst.encash_id', '=', 'vip_dtl.encash_id')
                ->join('customer', 'customer.custid', '=', 'vip_mst.custid')
                ->where('loc_code', Auth::user()->businessunit->loc_code_atp)
                ->where('vip_dtl.entry_no', '>', $this->getEncashLastEntryNo())
                ->where('vip_mst.encash_date', '>=', $dateStartEncash)
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
        } catch (\Exception $e) {
            exit();
        }

        $noChecks = intval(count($checks)) + intval(count($checkEnCash));

        $count = 1;
        $countPersistent = 1;
        $issueNumber = null;

        $noChecksDc = count($checkDatedCheck);

        if ($noChecksDc > 0) {
            $issueNumber = $checkDatedCheck[$noChecksDc - 1]->issue_no;
        }

        $lastCheckEncashNumber = null;
        $noCheckEncash = count($checkEnCash);

        if ($noCheckEncash > 0) {
            $lastCheckEncashNumber = $checkEnCash[$noCheckEncash - 1]->Entry_No;
        }

        ImportUpdateEvents::dispatch('Updating atp database...', 0, $noChecksDc, Auth::user());
        ImportUpdateAtpAllEvent::dispatch('Updating all checks...', 0, $noChecks, Auth::user());
        sleep(1);

        DB::transaction(function () use (&$count, &$countPersistent, &$checks, &$issueNumber, &$lastCheckEncashNumber, &$checkEnCash, &$noChecks, &$noCheckEncash, &$noChecksDc) {
            // $checkReceived = CheckRecieved::create([
            //     'checksreceivingtransaction_ctrlno' => $this->getControlNumber(),
            //     'id' => Auth::user()->id,
            //     'company_id' => Auth::user()->company_id,
            //     'businessunit_id' => Auth::user()->businessunit_id,
            //     'atp_issueno' => $issueNumber,
            //     'encash_entrynum' => $lastCheckEncashNumber,
            // ]);

            // $recID = $checkReceived->checksreceivingtransaction_id;
            // dd($recID);

            foreach ($checks as $check) {

                // dd($noChecks);
                $customer = "";
                $customerid = "";
                $bankname = "";
                $bankid = "";
                $ctype = "";
                $midname = "";
                $checkrec = "";
                $checkexpiry = "";
                $checkclass = "";
                $checkno = "";

                if (str_replace(' ', '', $check->chkexpiry) == '//' || trim($check->chkexpiry) == '') {
                    $checkexpiry = null;
                } else {
                    $checkexpiry = $check->chkexpiry;
                    $old_date_timestamp = strtotime($checkexpiry);
                    $checkexpiry = date('Y-m-d', $old_date_timestamp);
                }

                if (trim($check->cmiddname) != '.') {
                    $midname = trim($check->cmiddname);
                }
                // dd($check->extension);

                $customer = preg_replace('!\s+!', ' ', $check->cfirstname . ' ' . $midname . ' ' . $check->clastname . '' . $check->extension);
                $customer = trim($customer);
                // dd($customer);

                $daex = explode(" ", $check->chkdate);
                $datex = $daex[0];
                // dd(today()->toDateString(), $datex);

                if ($datex > today()->toDateString()) {
                    $ctype = "POST DATED";
                } else {
                    $ctype = "DATED CHECK";
                }

                if ($id = $this->isCustomerNameExist($customer)) {
                    $customerid = $id;
                } else {
                    $customerid = $this->autoCreateCustomer($customer);

                }

                $bankname = trim($check->bankname);

                if ($bid = $this->isBankExist($bankname)) {
                    $bankid = $bid; // result for bank name is exist
                } else {
                    // otherwise auto increment bank id
                    $bankid = $this->autoCreateNewBank($bankname);
                }

                $checkRecs = explode(" ", $check->atp_date);
                $checkRecs = $checkRecs[0];

                if (strpos($check->chkclass, 'PERSONAL') !== false) {
                    $checkclass = 'PERSONAL';
                } else {
                    $checkclass = $check->chkclass;
                }

                $checkNumber = trim(preg_replace("/\./", "", $check->chkno));

                $checkNumber = ltrim($checkNumber, '0');

                // Checks::create([
                //     'check_bounced_id' => 0,
                //     'is_exist' => 0,
                //     'is_manual_entry' => 0,
                //     'checksreceivingtransaction_id' => $recID,
                //     'customer_id' => $customerid,
                //     'check_no' => $checkNumber,
                //     'check_class' => trim($checkclass),
                //     'check_date' => trim($datex),
                //     'check_received' => trim($checkRecs),
                //     'check_type' => trim($ctype),
                //     'account_no' => trim($check->actno),
                //     'account_name' => trim($check->actname),
                //     'bank_id' => $bankid,
                //     'businessunit_id' => Auth::user()->businessunit_id,
                //     'check_amount' => trim(str_replace(',', '', $check->chkamt)),
                //     'check_expiry' => $checkexpiry,
                //     'check_category' => trim($check->category),
                //     'check_status' => 'PENDING',
                //     'approving_officer' => trim($check->approvedby) == '' ? null : trim($check->approvedby),
                //     'currency_id' => 1,
                //     'department_from' => 15,
                //     'user' => Auth::user()->id,
                //     'date_time' => today()->toDateString(),
                // ]);
                // dd('Updating atp database...', $count++, $noChecks, Auth::user()->id);
                // $countPersistent++;
                ImportUpdateAtpAllEvent::dispatch('Updating all checks...', $countPersistent++, $noChecks, Auth::user());
                ImportUpdateAtpEvent::dispatch('Updating Atp Database..', $count++, $noChecksDc, Auth::user());

            }
            
            $count = 1 ; 
            sleep(1);
            foreach ($checkEnCash as $check) {
              
                $customer = "";
                $customerid = "";
                $bankname = "";
                $bankid = "";
                $ctype = "";
                $midname = "";
                $checkrec = "";
                $checkexpiry = "";
                $checkclass = "";
                $checkno = "";

                if (str_replace(' ', '', $check->ChkExpiry) == '//' || trim($check->ChkExpiry) == '') {
                    $checkexpiry = null;
                } else {
                    $checkexpiry = $check->ChkExpiry;
                }
                if ($check->ChkType == 'PD') {
                    $ctype = "POST DATED";
                } else {
                    $ctype = "DATED CHECK";
                }

                if (trim($check->cmiddname) != '.') {
                    $midname = trim($check->cmiddname);
                }

                $customer = preg_replace('!\s+!', ' ', $check->cfirstname . ' ' . $midname . ' ' . $check->clastname . '' . $check->extension);
                $customer = trim($customer);

                $daex = explode(" ", $check->ChkDate);
                $datex = $daex[0];

                if ($id = $this->isCustomerNameExist($customer)) {
                    $customerid = $id;
                } else {
                    $customerid = $this->autoCreateCustomer($customer);

                }

                $bankname = trim($check->BankName);

                if ($bid = $this->isBankExist($bankname)) {
                    $bankid = $bid; // result for bank name is exist
                } else {
                    // otherwise auto increment bank id
                    $bankid = $this->autoCreateNewBank($bankname);
                }

                $checkRecs = explode(" ", $check->Encash_Date);
                $checkRecs = $checkRecs[0];

                if (strpos($check->ChkClass, 'PERSONAL') !== false) {
                    $checkclass = 'PERSONAL';
                } else {
                    $checkclass = $check->ChkClass;
                }

                $checkNumber = trim(preg_replace("/\./", "", $check->ChkNo));

                $checkNumber = ltrim($checkNumber, '0');

                // Checks::create([
                //     'check_bounced_id' => 0,
                //     'is_exist' => 0,
                //     'is_manual_entry' => 0,
                //     'checksreceivingtransaction_id' => $recID,
                //     'customer_id' => $customerid,
                //     'check_no' => $checkNumber,
                //     'check_class' => trim($checkclass),
                //     'check_date' => trim($datex),
                //     'check_received' => trim($checkRecs),
                //     'check_type' => trim($ctype),
                //     'account_no' => trim($check->ActNo),
                //     'account_name' => trim($check->ActName),
                //     'bank_id' => $bankid,
                //     'businessunit_id' => Auth::user()->businessunit_id,
                //     'check_amount' => trim(str_replace(',', '', $check->ChkAmt)),
                //     'check_expiry' => $checkexpiry,
                //     'check_category' => trim($check->Category),
                //     'check_status' => 'PENDING',
                //     'approving_officer' => trim($check->ApprovedBy) == '' ? null : trim($check->ApprovedBy),
                //     'currency_id' => 1,
                //     'department_from' => 13,
                //     'user' => Auth::user()->id,
                //     'date_time' => today()->toDateString(),
                // ]);
                // dd('Updating atp database...', $count++, $noChecks, Auth::user()->id);
            // $countPersistent++;
                ImportUpdateAtpAllEvent::dispatch('Updating all checks...', $countPersistent++, $noChecks, Auth::user());
                ImportUpdateAtpEvent::dispatch('Updating Check Encashment...', $count++, $noCheckEncash, Auth::user());
            }
        });
        sleep(1);
        return Inertia::render('Components/ImportUpdatePartials/ImportUpdateAtpResult');

    }

    public function getATPLastIssueNo()
    {
        $issueno = DB::table('checksreceivingtransaction')
            ->join('businessunit', 'businessunit.businessunit_id', '=', 'checksreceivingtransaction.businessunit_id')
            ->where('businessunit.loc_code_atp', Auth::user()->businessunit->loc_code_atp)
            ->whereNotNull('atp_issueno')
            ->select('atp_issueno')
            ->orderBy('checksreceivingtransaction.checksreceivingtransaction_id', 'desc')
            ->first();

        if (is_null($issueno)) {
            return 0;
        }
        return $issueno->atp_issueno;

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
