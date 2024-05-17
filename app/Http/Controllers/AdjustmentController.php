<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdjustmentController extends Controller
{
    //
    public function editChecksAdjustment(Request $request)
    {

        // dd($request->all());

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', '!=', 61)
            ->get();

        $data = NewSavedChecks::join('checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->where('checks.businessunit_id', $request->bunitId)
            ->whereYear('checks.check_received', $request->datedYear)
            ->select('checks.*', 'customers.*', 'department.*', 'banks.*')
            ->paginate(10)->withQueryString();

        // dd($data->toArray());
        $data->transform(function ($value) {
            $type = $value->check_date <= date('Y-m-d') ? 'DATED' : 'POST DATED';
            return $value;
        });
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
        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', '!=', 61)
            ->get();

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
    public function bounceChecksAdjustments()
    {

        return Inertia::render('Adjustments/DepositDetails', [
            'data' => $data,
            'bunit' => $bunit,
            'columns' => ColumnsHelper::$deposit_adjustment_columns,
            'yearBackend' => empty($request->datedYear) ? null : $request->datedYear,
            'bunitBackend' => empty($request->bunitId) ? [] : intval($request->bunitId),
        ]);
    }
}
