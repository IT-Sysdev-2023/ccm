<?php

namespace App\Http\Controllers;

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

        $checks = DB::table('checks')
            ->join('checksreceivingtransaction', 'checksreceivingtransaction.checksreceivingtransaction_id', '=', 'checks.checksreceivingtransaction_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->where('check_date', '<=', DB::raw('check_received'))
            ->where('checks.date_time', $request->generate_date)
            ->where('date_time', '!=', '0000-00-00')
            ->where('checksreceivingtransaction.businessunit_id', $request->user()->businessunit_id)
            ->orderBy('check_date', 'ASC')
            ->paginate(10);


        return Inertia::render('CheckReceiving/CheckForClearing', [
            'data' => $checks,
            'columns' => $this->columns->check_for_clearing_columns,
        ]);
    }
}
