<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use Illuminate\Support\Facades\Date;

class AllTransactionController extends Controller
{
    public function __construct(public ColumnsHelper $columns)
    {

    }
    //
    public function getCheckManualEntry(Request $request)
    {
        $data = DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'checks.department_from', '=', 'department.department_id')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('checks.is_manual_entry', '=', 1)
            ->where('new_saved_checks.status', '')
            ->where('checks.check_no', 'LIKE', '%' . $request->searchQuery . '%')
            ->orderBy('checks.check_received')
            ->paginate(20);


        $data->transform(function ($value) {
            $type = '';
            Date::parse($value->check_date)->lessThanOrEqualTo(today()) ? $type = 'DATED' : $type = 'POST DATED';
            $value->type = $type;



            return $value;
        });


        return Inertia::render('Transaction/CheckManualEntry', [
            'data' => $data,
            'columns' => $this->columns->check_manual_column,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }
    public function getMergeChecks(Request $request)
    {
        $data = DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('check_date', '>', DB::raw('check_received'))
            ->where('new_saved_checks.status', "")
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
            })
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->paginate(15);

        // dd($data);
        return Inertia::render('Transaction/MergeChecks', [
            'data' => $data,
            'columns' => $this->columns->merge_checks_column,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }
}
