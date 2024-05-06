<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\BusinessUnit;
use App\Models\Checks;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AccountingReportController extends Controller
{
    //
    public function reportIndex()
    {
        return Inertia::render('AccountingReports/ReportIndex');
    }
    public function innerReportDatedPdcCheques(Request $request)
    {
        // dd($request->all());

        $department_from = Checks::join('department', 'department.department_id', '=', 'checks.department_from')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->orderBy('department')
            ->cursor()
            ->groupBy('department_from');

        $bunit = BusinessUnit::whereNotNull('loc_code_atp')
            ->whereNotNull('b_atpgetdata')
            ->whereNotNull('b_encashstart')
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->cursor();


        $data = NewSavedChecks::join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->leftJoin('new_ds_checks', 'checks.checks_id', '=', 'new_ds_checks.checks_id')
            ->where('checks.department_from', 'like', '%' . $request->dataFrom . '%')
            ->where(function ($query) use ($request) {
                if ($request->dataType == 1) {
                    $query->where('check_date', '<=', DB::raw('check_received'));
                } elseif ($request->type == 2) {
                    $query->where('check_date', '>', DB::raw('check_received'));
                }else{

                }
            })
            ->where(function ($query) use ($request) {
                if ($request->dataStatus == 1) {
                    $query->whereNull('new_ds_checks.checks_id');
                } elseif ($request->dataStatus == 2) {
                    $query->whereNotNull('new_ds_checks.checks_id');
                }
            })
            ->where('businessunit_id', $request->user()->businessunit_id)
            ->paginate(10)->withQueryString();

            // dd($data);

        return Inertia::render('AccountingReports/InnerReports/DatedPostDatedChecks', [
            'data' => empty($request->all()) ? [] : $data,
            'columns' => ColumnsHelper::$acc_dated_pdc_reports,
            'department_from' => $department_from,
            'bunit' => $bunit,
            'dataTypeBackend' => $request->dataType,
            'dataStatusBackend' => $request->dataStatus,
        ]);
    }
}
