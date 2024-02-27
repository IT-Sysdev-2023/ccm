<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use Illuminate\Http\Request;
use App\Helper\ColumnsHelper;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class CheckReceivingController extends Controller
{
    //

    public function __construct(public ColumnsHelper $columns)
    {

    }
    public function getCheckForClearing(Request $request)
    {

        $q = Checks::join('checksreceivingtransaction', 'checksreceivingtransaction.checksreceivingtransaction_id', '=', 'checks.checksreceivingtransaction_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->where('check_date', '<=', DB::raw('check_received'))
            ->whereDate('checks.date_time', $request->generate_date)
            ->where('date_time', '!=', '0000-00-00')
            ->where('checksreceivingtransaction.businessunit_id', $request->user()->businessunit_id)
            ->orderBy('check_date', 'ASC');


        $q = match ($request->check_status) {
            'CLEARED' => $q->where('check_status', 'CLEARED'),
            'PENDING' => $q->where('check_status', 'PENDING'),
            'CASH' => $q->where('check_status', 'CASH'),
            'BOUNCE' => $q->where('check_status', 'BOUNCE'),
            'ALL' => $q,
            default => $q
        };

        $data = $q->paginate(10)->withQueryString();
        // dd($data);



        return Inertia::render('CheckReceiving/CheckForClearing', [
            'data' => $data,
            'columns' => $this->columns->check_for_clearing_columns,
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
            'date' => $request->generate_date ?? today(),
            'value' => $request->check_status
        ]);
    }
}
