<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\NewBounceCheck;
use App\Models\NewCheckReplacement;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdjustmentController extends Controller
{
    //
    public function editChecksAdjustment(Request $request)
    {

        // dd($request->all());

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')->get();

        $data = NewSavedChecks::join('checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->where('checks.businessunit_id', $request->bunitId)
            ->whereYear('checks.check_received', $request->datedYear)
            ->select('checks.*', 'customers.*', 'department.*', 'banks.*')
            ->paginate(10)->withQueryString();

        // dd($data->toArray());

        return Inertia::render('Adjustments/EditCheckDetails', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$checks_table_columns,
            'yearBackend' => empty($request->datedYear) ? null : $request->datedYear,
            'bunitBackend' => empty($request->bunitId) ? [] : intval($request->bunitId),
        ]);
    }

    public function updateCheckAdjustments(Request $request)
    {
        // dd($request->all());
        Checks::where('checks_id', $request->checks_id)->update([
            'customer_id' => $request->customer_id,
            'check_no' => $request->check_no,
            'check_class' => $request->check_class,
            'account_no' => $request->account_no,
            'account_name' => $request->account_name,
            'check_category' => $request->check_category,
            'department_from' => $request->deparment_id,
            'approving_officer' => $request->approving_officer,
            'check_date' => $request->check_date,
            'check_received' => $request->check_received,
            'bank_id' => $request->bank_id,
            'check_amount' => $request->check_amount,
        ]);
        return redirect()->back();
    }

    public function depositAdjustments(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')->get();

        $data = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('users', 'new_ds_checks.user', 'users.id')
            ->where('checks.businessunit_id', $request->bunitId)
            ->whereYear('checks.check_received', $request->datedYear)
            ->where('new_ds_checks.status', '=', '')
            ->select('checks.*', 'customers.*', 'users.*', 'new_ds_checks.ds_no', 'new_ds_checks.user', 'new_ds_checks.date_time', 'new_ds_checks.date_deposit')
            ->orderBy('new_ds_checks.date_time', 'desc')
            ->paginate(10)->withQueryString();

        return Inertia::render('Adjustments/DepositDetails', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$deposit_adjustment_columns,
            'yearBackend' => empty($request->datedYear) ? null : $request->datedYear,
            'bunitBackend' => empty($request->bunitId) ? [] : intval($request->bunitId),
        ]);
    }
    public function updateDsNumber(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'dsNumber' => 'required',
        ]);

        NewDsChecks::where('checks_id', $request->checksId)->update(['ds_no' => $request->dsNumber]);

        return redirect()->back();
    }
    public function bounceChecksAdjustments(Request $request)
    {
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')->get();

        $data = NewBounceCheck::join('checks', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('new_bounce_check.status', '=', '')
            ->where('checks.businessunit_id', $request->bunitId)
            ->select('check_received', 'check_date', 'new_bounce_check.date_time', 'fullname', 'check_no', 'new_bounce_check.checks_id', 'check_amount', 'new_bounce_check.id')
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) {
            $value->type = $value->check_date <= date('Y-m-d') ? 'DATED' : 'POST DATED';
            $value->statusType = 'BOUNCE';

            return $value;
        });

        // dd($data->toArray());

        return Inertia::render('Adjustments/BounceDetails', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$bounce_adjustment_columns,
            'bunitBackend' => empty($request->bunitId) ? [] : intval($request->bunitId),
        ]);
    }

    public function reBounceCheckAdjustments(Request $request)
    {
        // dd(1);
        DB::transaction(function () use ($request) {

            Checks::where('checks_id', $request->checksId)->update(['check_status' => 'CLEARED']);

            NewSavedChecks::where('checks_id', $request->checksId)->update(['status' => '']);

            NewDsChecks::where('checks_id', $request->checksId)->update(['status' => '']);

            NewBounceCheck::where('checks_id', $request->checksId)->delete();

        });

        return redirect()->back();

    }
    public function replacementAdjustments(Request $request)
    {
        $bunitData = BusinessUnit::whereNotNull('loc_code_atp')->get();

        $data = NewCheckReplacement::join('checks', 'checks.checks_id', '=', 'new_check_replacement.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('users', 'users.id', '=', 'new_check_replacement.user')
            ->where('new_check_replacement.status', '!=', '')
            ->where('checks.businessunit_id', $request->bunit)
            ->orderBy('new_check_replacement.id', 'desc')
            ->select('*', 'new_check_replacement.date_time')
            ->paginate(10)->withQueryString();

        return Inertia::render('Adjustments/ReplacementChecks', [
            'bunit' => $bunitData,
            'columns' => ColumnsHelper::$replaced_check_columns,
            'data' => $data,
            'bunitBackend' => empty($request->bunit) ? null : intval($request->bunit),
        ]);
    }

    public function updateRedepositChecks(Request $request)
    {
        DB::transaction(function () use ($request) {
            NewCheckReplacement::where('id', $request->replacement_id)->first()
                ->update([
                    'penalty' => str_replace(",", "", $request->penalty),
                    'ar_ds' => $request->ar_ds,
                    'reason' => $request->reason,
                ]);

            $checks = NewCheckReplacement::where('id', $request->replacement_id)
                ->select('checks_id')
                ->first();

            NewDsChecks::where('checks_id', $checks->checks_id)
                ->where('status', '!=', 'BOUNCED')
                ->update(['ds_no' => $request->ar_ds]);
        });
    }
    public function cancelRedepositChecks(Request $request)
    {
        DB::transaction(function () use ($request) {

            $replacement = NewCheckReplacement::where('id', $request->replacement_id)
                ->first();

            NewSavedChecks::where('checks_id', $replacement->checks_id)
                ->update(['status' => 'BOUNCED']);

            Checks::where('checks_id', $replacement->checks_id)
                ->update(['check_status' => 'BOUNCE']);

            NewBounceCheck::where('id', '=', $replacement->bounce_id)
                ->update(['status' => '']);

            NewDsChecks::where('checks_id', $replacement->checks_id)
                ->where('status', '!=', 'BOUNCED')
                ->delete();

            NewCheckReplacement::where('id', $request->replacement_id)
                ->delete();
        });
    }
    public function updateCashAdjustments(Request $request)
    {
        NewCheckReplacement::where('id', $request->replacement_id)->first()
            ->update([
                'penalty' => str_replace(",", "", $request->penalty),
                'ar_ds' => $request->ar_ds,
                'reason' => $request->reason,
            ]);
    }
    public function cancelCashAdjustments(Request $request)
    {
        DB::transaction(function () use ($request) {
            $replacements = NewCheckReplacement::where('id', $request->replacement_id)
                ->first();

            if ($request->rep_status == 'BOUNCED') {
                NewBounceCheck::where('id', '=', $replacements->bounce_id)
                    ->update(['status' => '']);

                Checks::where('checks_id', $replacements->checks_id)
                    ->update(['check_status' => 'BOUNCE', 'cash' => null]);

                NewCheckReplacement::where('id', $request->replacement_id)
                    ->delete();
            } else {
                NewSavedChecks::where('checks_id', '=', $replacements->checks_id)
                    ->update(['status' => '']);

                Checks::where('checks_id', $replacements->checks_id)
                    ->update(['check_status' => 'CLEARED', 'cash' => null]);

                NewCheckReplacement::where('id', $request->replacement_id)
                    ->delete();
            }
        });
    }
    public function updateCashAndCheckAdjustments(Request $request)
    {
        DB::transaction(function () use ($request) {
            NewCheckReplacement::where('id', $request->replacement_id)
                ->update([
                    'check_amount_paid' => str_replace(",", "", $request->check_amount),
                    'penalty' => str_replace(",", "", $request->penalty),
                    'ar_ds' => $request->ar_ds,
                    'reason' => $request->reason,
                    'cash' => str_replace(",", "", $request->cash),
                ]);

            Checks::where('checks_id', $request->rep_check_id)
                ->update([
                    'check_no' => $request->check_no,
                    'check_amount' => str_replace(",", "", $request->check_amount),
                    'cash' => str_replace(",", "", $request->cash),
                ]);
        });
    }
    public function updateCheckForAdjustments(Request $request)
    {
        DB::transaction(function () use ($request) {
            NewCheckReplacement::where('id', $request->replacement_id)
                ->update([
                    'check_amount_paid' => str_replace(",", "", $request->check_amount),
                    'penalty' => str_replace(",", "", $request->penalty),
                    'ar_ds' => $request->ar_ds,
                    'reason' => $request->reason,
                ]);

            Checks::where('checks_id', $request->rep_check_id)
                ->update([
                    'check_no' => $request->check_no,
                    'check_amount' => str_replace(",", "", $request->check_amount),
                ]);
        });
    }
    public function cancelCheckAdjustments(Request $request)
    {
        $replacement = NewCheckReplacement::where('id', $request->replacement_id)
            ->first();

        if ($request->repStatus == 'BOUNCED') {
            NewBounceCheck::where('id', '=', $replacement->bounce_id)
                ->update(['status' => '']);

            Checks::where('checks_id', $request->rep_check_id)
                ->delete();

            NewSavedChecks::where('checks_id', $request->rep_check_id)
                ->delete();

            NewCheckReplacement::where('id', $request->replacement_id)
                ->delete();
        } else {
            NewSavedChecks::where('checks_id', '=', $replacement->checks_id)
                ->update(['status' => '']);

            Checks::where('checks_id', $request->rep_check_id)
                ->delete();

            NewSavedChecks::where('checks_id', $request->rep_check_id)
                ->delete();

            NewCheckReplacement::where('id', $request->replacement_id)
                ->delete();
        }
    }
    // public function altaCittaCheckAdjustments()
    // {
    //     $data = NewSavedChecks::join('checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
    //         ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
    //         ->join('department', 'department.department_id', '=', 'checks.department_from')
    //         ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
    //         ->where('checks.businessunit_id', '61')
    //         ->select('checks.*', 'customers.*', 'department.*', 'banks.*')
    //         ->paginate(10)->withQueryString();

    //     return Inertia::render('Adjustments/AltaCittaDetails', [
    //         'data' => $data,
    //         'columns' => ColumnsHelper::$alta_citta_columns,
    //     ]);
    // }
}
