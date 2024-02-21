<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class DatedPdcChecksController extends Controller
{

    public function __construct(public ColumnsHelper $columns)
    {

    }
    public function pdc_index(Request $request)
    {

        $data = collect(DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'checks.department_from', '=', 'department.department_id')
            ->where('check_date', '>', DB::raw('check_received'))
            ->where('new_saved_checks.status', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
            })
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->get());

        return Inertia::render('Dated&PdcChecks/PDCChecks', [
            'data' => $data,
            'columns' => $this->columns->pdc_check_columns,
        ]);
    }
    public function dated_index(Request $request)
    {

        $data = DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('check_date', '<=', DB::raw('check_received'))
            ->where('new_saved_checks.status', "")
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
            })
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->get();

        $data->transform(function ($value) {

            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            return $value;
        });


        return Inertia::render('Dated&PdcChecks/DatedChecks', [
            'data' => $data,
            'columns' => $this->columns->dated_check_columns,
        ]);
    }
}
