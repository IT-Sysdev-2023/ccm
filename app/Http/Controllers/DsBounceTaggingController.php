<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Models\NewBounceCheck;
use App\Models\CheckHistory;
use App\Models\Checks;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Inertia\Inertia;

class DsBounceTaggingController extends Controller
{
    public function indexBounceTagging()
    {
        return Inertia::render('Ds&BounceTagging/BounceTagging');
    }
    public function updateSwitch(Request $request)
    {
        NewSavedChecks::where('checks_id', $request->id)
            ->update([
                'done' => $request->isCheck ? "check" : ""
            ]);

        $amount = $request->oldAmount;
        $count = $request->oldCount;

        if ($request->isCheck) {
            $count++;
            $amount += NumberHelper::float($request->checkAmount);
        } else {
            $count--;
            $amount -= NumberHelper::float($request->checkAmount);
        }

        return response()->json(['newAmount' => $amount, 'newCount' => $count]);
    }
    public function indexDsTagging(Request $request)
    {
        $due_dates = NewSavedChecks::dsTaggingQuery(Auth::user()->businessunit_id)
            ->whereDate('checks.check_date', today())
            ->count();

        $ds_checks_table = NewSavedChecks::dsTaggingQuery(Auth::user()->businessunit_id)
            ->orderBy('checks.check_received', 'DESC')
            ->paginate(550);

        $ds_checks_table->transform(function ($value) {

            $value->type = Date::parse($value->check_date)->lessThanOrEqualTo(today()) ? 'DATED' : 'POST-DATED';
            $value->done = empty ($value->done) ? false : true;
            $value->check_received = Date::parse($value->check_received)->toFormattedDateString();
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        $getAmount = $ds_checks_table->where('done', true);
        $totalAmountActive = $getAmount->sum(fn($item) => $item->check_amount);

        return Inertia::render('Ds&BounceTagging/DsTagging', [
            'due_dates' => $due_dates,
            'total' => [
                'totalSum' => (float) $totalAmountActive,
                'count' => $getAmount->count()
            ],
            'ds_c_table' => $ds_checks_table,
            'columns' => ColumnsHelper::$columns_ds_tagging,
        ]);
    }

    public function get_bounce_tagging(Request $request)
    {
        ini_set('memory_limit', '-1');

        $data = NewDsChecks::join('checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('users', 'new_ds_checks.user', 'users.id')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->join('department', 'department.department_id', 'checks.department_from')
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('new_ds_checks.status', '=', '')
            ->select('checks.*', 'customers.*', 'users.*', 'new_ds_checks.ds_no', 'new_ds_checks.user', 'new_ds_checks.date_time', 'new_ds_checks.date_deposit', 'department.department', 'banks.*')
            ->where('checks.check_no', 'like', '%' . $request->search . '%')
            ->whereYear('checks.check_received', $request->dt_year)
            ->orderBy('new_ds_checks.date_time', 'desc')
            ->orderBy('checks.check_received', 'desc')->paginate(20);

        $data->transform(function ($value) {
            $value->check_received = Date::parse($value->check_received)->toFormattedDateString();
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'current' => $data->currentPage(),
                'total' => $data->total(),
                'pageSize' => $data->perPage(),
            ],
        ]);
    }

    public function tag_check_bounce(Request $request)
    {

        DB::transaction(function () use ($request) {

            Checks::findChecks($request->check_id)->update(['check_status' => 'BOUNCE']);
            NewSavedChecks::findChecks($request->check_id)->update(['status' => 'BOUNCED']);

            CheckHistory::create([
                'checks_id' => $request->check_id,
                'status' => 'bounce',
                'date_time' => $request->date,
                'user' => Auth::user()->id
            ]);

            NewBounceCheck::create([
                'checks_id' => $request->check_id,
                'check_type' => 'bounce',
                'status' => '',
                'date_time' => $request->date,
                'user' => Auth::user()->id
            ]);

        });

    }

    public function dummy()
    {
        $dummy = Checks::with('checkreceived')->get();
    }

    public function submiCheckDs(Request $request)
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
                    'date_time' => now()
                ]);

            });
        });

        return redirect()->back();
    }

}
