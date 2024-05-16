<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\BusinessUnit;
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
            ->join('department', 'department.department_id', '=' , 'checks.department_from')
            ->where('checks.businessunit_id', $request->bunitId)
            ->whereYear('checks.check_received', $request->datedYear)
            ->select('checks.*', 'customers.*' , 'department.*')
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
}
