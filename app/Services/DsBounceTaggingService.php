<?php

namespace App\Services;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Http\Resources\NewDsCheckResource;
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

        return $data;
    }

    public function get_bounce_tagging(Request $request)
    {
        $datedYear = $request->year ?? now()->toDateString();

        $data = NewDsChecks::with(
            'check:checks_id,customer_id,check_date,date_deposit,check_no,check_amount,check_received,check_no',
            'check.customer:customer_id,fullname',
            'user:id,',
        )->whereHas(
            'check',
            fn ($query) =>
            $query->where('businessunit_id',  $request->user()->businessunit_id)
                ->whereYear('check_received', $datedYear)
        )->where('new_ds_checks.status', '=', '')
            ->selectFilter()
            ->searchFilter($request)
            ->orderBy('new_ds_checks.date_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return $data;
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
