<?php

namespace App\Services;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Http\Resources\NewSavedCheckResource;
use App\Models\CheckHistory;
use App\Models\Checks;
use App\Models\NewBounceCheck;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DsBounceTaggingService
{


    public function __construct(public NewSavedChecks $newSavedChecks)
    {
    }

    public function updateSwitch(Request $request)
    {
        $this->newSavedChecks->findChecks($request->id)
            ->update([
                'done' => $request->isCheck ? "check" : "",
            ]);
        return redirect()->back();
    }

    public function indexDsTagging(Request $request)
    {
        $filter = (is_null($request->tab) || $request->tab == '1') ? '' : 'check';

        $data = NewSavedChecks::dsTaggingQuery($request->user()->businessunit_id)
            ->whereSearchFilter($request)
            ->selectFilter()
            ->where('done', $filter)
            ->orderBy('checks.check_received', 'DESC');

        return Inertia::render('Ds&BounceTagging/DsTagging', [
            'due_dates' => self::duedatesCounts($request),
            'total' => [
                'totalSum' => (float) $data->sum('check_amount'),
                'count' => $data->count(),
            ],
            'data' => NewSavedCheckResource::collection($data->paginate(10)->withQueryString()),
            'columns' => ColumnsHelper::$columns_ds_tagging,
            'filters' => $request->only(['search']),
            'tab' => $request->tab ?? '1',
        ]);
    }

    public static function duedatesCounts($request)
    {
        return NewSavedChecks::dsTaggingQuery($request->user()->businessunit_id)
            ->whereDate('checks.check_date', today()->toDateString())
            ->count();
    }

    public function get_bounce_tagging(Request $request)
    {
        ini_set('memory_limit', '-1');

        $datedYear = $request->year ?? now()->toDateString();

        $data = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('users', 'new_ds_checks.user', 'users.id')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->join('department', 'department.department_id', 'checks.department_from')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('new_ds_checks.status', '=', '')
            ->select(
                'checks.*',
                'customers.*',
                'users.*',
                'new_ds_checks.ds_no',
                'new_ds_checks.user',
                'new_ds_checks.date_time',
                'new_ds_checks.date_deposit',
                'department.department',
                'banks.*',
            )
            ->whereAny([
                'checks.check_no',
                'checks.check_amount',
                'customers.fullname',
                'new_ds_checks.ds_no',
            ], 'LIKE', '%' . $request->search)
            ->whereYear('checks.check_received', $datedYear)
            ->orderBy('new_ds_checks.date_time', 'desc')
            ->orderBy('checks.check_received', 'desc')->paginate(10)->withQueryString();




        $data->transform(function ($value) {
            $value->check_received = Date::parse($value->check_received)->toFormattedDateString();
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->date_deposit = Date::parse($value->date_deposit)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return Inertia::render('Ds&BounceTagging/BounceTagging', [
            'data' => $data,
            'columns' => ColumnsHelper::$get_bounce_tagging_columns,
            'filters' => $request->only(['year', 'search']),
        ]);
    }

    public function tagCheckBounce(Request $request)
    {

        return DB::transaction(function () use ($request) {

            Checks::findChecks($request->check_id)->update(['check_status' => 'BOUNCE']);
            NewSavedChecks::findChecks($request->check_id)->update(['status' => 'BOUNCED']);

            CheckHistory::create([
                'checks_id' => $request->check_id,
                'status' => 'bounce',
                'date_time' => $request->date,
                'user' => Auth::user()->id,
            ]);

            NewBounceCheck::create([
                'checks_id' => $request->check_id,
                'check_type' => 'bounce',
                'status' => '',
                'date_time' => $request->date,
                'user' => Auth::user()->id,
            ]);
        });
    }
    public function submiCheckDs(Request $request): RedirectResponse
    {
        $request->validate([
            'dsNo' => 'required',
            'dateDeposit' => 'required|date',
        ]);

        collect($request->selected)->each(function ($check) use ($request) {

            DB::transaction(function () use ($check, $request) {

                NewSavedChecks::findChecks($check['checks_id'])->update(['ds_status' => 'remitted']);

                NewDsChecks::create([
                    'checks_id' => $check['checks_id'],
                    'ds_no' => $request->dsNo,
                    'date_deposit' => $request->dateDeposit,
                    'user' => $request->user()->id,
                    'status' => '',
                    'date_time' => now(),
                ]);
            });
        });

        return redirect()->back();
    }
}
