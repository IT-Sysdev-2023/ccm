<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DsBounceTaggingController extends Controller
{
    //
    public function indexBounceTagging()
    {
        return Inertia::render('Ds&BounceTagging/BounceTagging');
    }
    public function indexDsTagging()
    {
        return Inertia::render('Ds&BounceTagging/DsTagging');
    }
    public function get_bounce_tagging(Request $request)
    {
        ini_set('memory_limit', '-1');
        
        $q = DB::table('new_ds_checks')
            ->join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('users', 'new_ds_checks.user', 'users.id')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->join('department', 'department.department_id', 'checks.department_from')
            ->where('checks.businessunit_id', Auth::user()->businessunit_id)
            ->where('new_ds_checks.status', '=', '')
            ->select('checks.*', 'customers.*', 'users.*', 'new_ds_checks.ds_no', 'new_ds_checks.user', 'new_ds_checks.date_time', 'new_ds_checks.date_deposit', 'department.department', 'banks.*')
            ->orderBy('new_ds_checks.date_time', 'desc')
            ->orderBy('checks.check_received', 'desc');
            

        $q = match($request->dt_year){
            $request->dt_year =>  $q->whereYear('checks.check_received', $request->dt_year),
            default => $q
        };  

        $data = $q->paginate(20)->withQueryString();

        // dd($data);
    

        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }

}
