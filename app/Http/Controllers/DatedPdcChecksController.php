<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Models\Checks;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class DatedPdcChecksController extends Controller
{
    public function pdc_index(Request $request)
    {

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('checks.check_date', '>', 'checks.check_received')
            ->paginate(20);

        $data->transform(function ($value) {
            $value->check_received = Date::parse($value->check_received)->toFormattedDateString();
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return Inertia::render('Dated&PdcChecks/PDCChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$pdc_check_columns,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }
    public function dated_index(Request $request)
    {

        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('check_date', '<=', 'check_received')
            ->paginate(20);

        $data->transform(function ($value) {
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return Inertia::render('Dated&PdcChecks/DatedChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$dated_check_columns,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],

        ]);
    }
}
