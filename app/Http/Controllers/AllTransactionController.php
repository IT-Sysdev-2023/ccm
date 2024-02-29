<?php

namespace App\Http\Controllers;

use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use Illuminate\Support\Facades\Date;

class AllTransactionController extends Controller
{
    public function getCheckManualEntry(Request $request)
    {
        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('checks.is_manual_entry', '=', 1)
            ->where('new_saved_checks.status', '')
            ->where('checks.check_no', 'LIKE', '%' . $request->searchQuery . '%')
            ->orderBy('checks.check_received')
            ->paginate(20);

        $data->transform(function ($value) {
            $value->type = Date::parse($value->check_date)->lessThanOrEqualTo(today()) ? 'DATED' : 'POST DATED';
            return $value;
        });


        return Inertia::render('Transaction/CheckManualEntry', [
            'data' => $data,
            'columns' => ColumnsHelper::$check_manual_column,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }
    public function getMergeChecks(Request $request)
    {
        $data = NewSavedChecks::joinChecksCustomer()->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereColumn('check_date', '>', 'check_received')
            ->paginate(15);

        return Inertia::render('Transaction/MergeChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$merge_checks_column,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }
}
