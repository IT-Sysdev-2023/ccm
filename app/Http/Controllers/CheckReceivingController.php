<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use App\Helper\ColumnsHelper;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Helper\NumberHelper;

class CheckReceivingController extends Controller
{
    public function getCheckForClearing(Request $request)
    {

        $dataChecksFn = Checks::joinCheckRecCustomerDepartmentBanks()
            ->whereDateTimeNotZero()
            ->whereColumn('check_date', '<=', 'check_received')
            ->where('check_status', 'PENDING')
            ->where('checksreceivingtransaction.businessunit_id', $request->user()->businessunit_id)
            ->orderBy('check_date', 'ASC')->get();

        $q = Checks::joinCheckRecCustomerDepartmentBanks()
            ->whereDateChecks($request->date)
            ->whereCheckNo($request->search)
            ->whereDateTimeNotZero()
            ->whereColumn('check_date', '<=', 'check_received')
            ->where('checksreceivingtransaction.businessunit_id', $request->user()->businessunit_id)
            ->orderBy('check_date', 'ASC');


        $q = match ($request->status) {
            'CLEARED' => $q->where('check_status', 'CLEARED'),
            'PENDING' => $q->where('check_status', 'PENDING'),
            'CASH' => $q->where('check_status', 'CASH'),
            'BOUNCE' => $q->where('check_status', 'BOUNCE'),
            'ALL' => $q,
            default => $q
        };

        $data = $q->paginate(10)->withQueryString();

        return Inertia::render('CheckReceiving/CheckForClearing', [
            'data' => $data,
            'columns' => ColumnsHelper::$check_for_clearing_columns,
            'filters' => $request->only([
                'search',
                'date',
                'status'
            ]),
            'dataFn' => $dataChecksFn,
        ]);
    }
    public function checkAndUncheck(Request $request)
    {
        if ($request->is_exist == 'true') {
            $check_data = Checks::findChecks($request->checksId)->first();

            $exist = Checks::where('check_no', $check_data->check_no)
                ->where('check_amount', $check_data->check_amount)
                ->where('checks_id', '!=', $request->checksId)
                ->where('checks.date_time', $check_data->date_time)
                ->where('is_exist', 1)
                ->exists();

            if ($exist) {

                return redirect()->back()->with([
                    'success' => 'The verification process employs a double-entry system
                    ',
                    'style' => 'red'
                ]);
            } else {
                Checks::where('checks_id', $request->checksId)->update(['is_exist' => 1]);

                return redirect()->back()->with([
                    'success' => 'Check Successfully',
                    'style' => 'green'
                ]);
            }

        } else {
            Checks::where('checks_id', $request->checksId)->update(['is_exist' => 0]);
            return redirect()->back()->with([
                'success' => 'Uncheck Successfully',
                'style' => 'green'
            ]);
        }
    }
    public function savedDatedLeasingpPdcChecks(Request $request)
    {
        collect($request->selected)->each(function ($check) use ($request) {
            $formdate = Carbon::createFromFormat('M d, Y', $check['check_date'])->format('Y-m-d');
            DB::transaction(function () use ($check, $request, $formdate) {

                Checks::findChecks($check['checks_id'])->update(['check_status' => 'CLEARED']);
                NewSavedChecks::create([
                    'checks_id' => $check['checks_id'],
                    'remarks' => $request->remarks,
                    'check_type' => $formdate,
                    'user' => $request->user()->id,
                    'date_time' => now(),
                    'status' => '',
                    'ds_status' => '',
                    'receive_status' => '',
                    'done' => ''
                ]);

            });

        });

        return redirect()->back()->with('success', 'Checks have been successfully saved.');
    }

    public function pdcChecksCLearing(Request $request)
    {

        // dd($request->all());
        $dataChecksFn = Checks::joinCheckRecCustomerDepartmentBanks()
            ->whereDateTimeNotZero()
            ->whereColumn('check_date', '>', 'check_received')
            ->where('checksreceivingtransaction.businessunit_id', $request->user()->businessunit_id)
            ->where('check_status', 'PENDING')
            ->orderBy('check_date', 'ASC')->cursor();

        $q = Checks::joinCheckRecCustomerDepartmentBanks()
            ->whereDateChecks($request->date)
            ->whereFilter($request->search)
            ->whereDateTimeNotZero()
            ->whereColumn('check_date', '>', 'check_received')
            ->where('checksreceivingtransaction.businessunit_id', $request->user()->businessunit_id)
            ->orderBy('check_date', 'ASC');


        $q = match ($request->status) {
            'CLEARED' => $q->where('check_status', 'CLEARED'),
            'PENDING' => $q->where('check_status', 'PENDING'),
            'CASH' => $q->where('check_status', 'CASH'),
            'BOUNCE' => $q->where('check_status', 'BOUNCE'),
            'ALL' => $q,
            default => $q
        };

        $data = $q->paginate(10)->withQueryString();

        return Inertia::render('CheckReceiving/PDCCheckCLearing', [
            'data' => $data,
            'columns' => ColumnsHelper::$pdc_check_clearing_column,
            'filters' => $request->only([
                'search',
                'date',
                'status'
            ]),
            'dataFn' => $dataChecksFn
        ]);
    }
    public function getLeasingChecks(Request $request)
    {
        $dataChecksFn = Checks::joinCheckRecCustomerDepartmentBanks()
            ->whereColumn('check_date', '>', 'check_received')
            ->where('leasing_docno', '!=', '')
            ->where('check_status', 'PENDING')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->orderBy('check_date', 'ASC')->cursor();

        $q = Checks::joinCheckRecCustomerDepartmentBanks()
            ->whereDateChecks($request->generate_date)
            ->whereCheckNo($request->searchQuery)
            ->whereColumn('check_date', '>', 'check_received')
            ->where('leasing_docno', '!=', '')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
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

        return Inertia::render('CheckReceiving/LeasingChecks', [
            'data' => $data,
            'columns' => ColumnsHelper::$leasing_checks_columns,
            'date' => $request->generate_date ?? today(),
            'value' => $request->check_status,
            'dataFn' => $dataChecksFn,
        ]);

    }
}
