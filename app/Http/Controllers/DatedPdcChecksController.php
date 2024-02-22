<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Models\NewSavedChecks;
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

        $data = NewSavedChecks::joinChecksCustomer()
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'checks.department_from', '=', 'department.department_id')
            ->where('check_date', '>', DB::raw('check_received'))
            ->where('new_saved_checks.status', "")
            ->doesntHave('dsCheck.check')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->get();

        return Inertia::render('Dated&PdcChecks/PDCChecks', [
            'data' => $data,
            'columns' => $this->columns->pdc_check_columns,
        ]);
    }
    public function dated_index(Request $request)
    {

        $data = NewSavedChecks::joinChecksCustomer()
            ->where('check_date', '<=', DB::raw('check_received'))
            ->where('new_saved_checks.status', "")
            ->doesntHave('dsCheck.check')
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
