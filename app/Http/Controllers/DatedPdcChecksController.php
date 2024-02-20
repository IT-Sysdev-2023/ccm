<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DatedPdcChecksController extends Controller
{
    public function dated_pdc_index(Request $request)
    {

        $data = collect(DB::table('new_saved_checks')
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('check_date', '>', DB::raw('check_received'))
            ->where('new_saved_checks.status', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
            })
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->get());

        $columns = [
            [
                'title' => 'Customer Name',
                'dataIndex' => 'fullname',
                'key' => 'check_r',
                'ellipsis' => true,
                'width' => '25%',
            ],
            [
                'title' => 'Check Number',
                'dataIndex' => 'check_no',
                'key' => 'check_r',
                'ellipsis' => true,
                'width' => '15%',
            ],
            [
                'title' => 'Check Date',
                'dataIndex' => 'check_date',
                'key' => 'check_r',
                'ellipsis' => true,
                'width' => '15%',
            ],
            [
                'title' => 'Amount',
                'dataIndex' => 'check_amount',
                'key' => 'check_r',
                'ellipsis' => true,
                'width' => '15%',
            ],
            [
                'title' => 'Amount',
                'dataIndex' => 'check_amount',
                'key' => 'check_r',
                'ellipsis' => true,
                'width' => '15%',
            ],
            [
                'title' => 'Action',
                'key' => 'action',
                'ellipsis' => true,
                'width' => '10%',
                'align' => 'center',
            ],
        ];


        return Inertia::render('Dated&PdcChecks/DatedChecks', [
            'data' => $data,
            'columns' => $columns,
        ]);
    }
}
