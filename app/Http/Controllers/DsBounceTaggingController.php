<?php

namespace App\Http\Controllers;

use App\Models\BouncedCheck;
use App\Models\CheckHistory;
use App\Models\Checks;
use App\Models\DsNumber;
use App\Models\SavedCheck;
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
    //
    public function indexBounceTagging()
    {
        return Inertia::render('Ds&BounceTagging/BounceTagging');
    }
    public function updateSwitch(Request $request)
    {

        SavedCheck::where('checks_id', $request->id)
            ->update([
                'done' => $request->isCheck ? "check" : ""
            ]);

        $amount = $request->oldAmount;
        $count = $request->oldCount;

        if ($request->isCheck) {
            $count++;
            $amount += (float) str_replace(',', '', $request->checkAmount);
        } else {
            $count--;
            $amount -= (float) str_replace(',', '', $request->checkAmount);
        }

        return response()->json(['newAmount' => $amount, 'newCount' => $count]);
    }
    public function indexDsTagging(Request $request)
    {
        $due_dates = SavedCheck::dsTaggingQuery($request->user()->businessunit_id)
            ->whereDate('checks.check_date', today())
            ->count();

        $ds_checks_table = SavedCheck::dsTaggingQuery(Auth::user()->businessunit_id)
            ->orderBy('checks.check_received', 'DESC')
            ->paginate(550);

        // $ds_checks_table = SavedCheck::dsTaggingQuery(Auth::user()->businessunit_id)
        //     ->orderBy('checks.check_received', 'DESC')
        //     ->whereNotExists(function ($query) {
        //         $query->select(DB::raw(1))
        //             ->from('new_ds_checks')
        //             ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
        //     })->paginate(550);

        foreach ($ds_checks_table as $value) {

            $type = '';
            if (Date::parse($value->check_date)->lessThanOrEqualTo(today())) {
                $type = 'DATED';
            } else {
                $type = 'POST-DATED';
            }
            $value->check_received = date('F j, Y', strtotime($value->check_received));
            $value->check_date = date('F j, Y', strtotime($value->check_date));
            $value->check_amount = number_format($value->check_amount, 2);
            $value->type = $type;

            $columns = [
                [
                    'title' => 'Checkreceived',
                    'dataIndex' => 'check_received',
                    'key' => 'check_r',
                    'ellipsis' => true,
                    'width' => '10%',
                ],
                [
                    'title' => 'Checkdate',
                    'dataIndex' => 'check_date',
                    'key' => 'check_d',
                    'ellipsis' => true,
                    'width' => '10%',

                ],
                [
                    'title' => 'Customer',
                    'dataIndex' => 'fullname',
                    'key' => 'fullname',
                    'ellipsis' => true,
                    'width' => '30%',
                ],
                [
                    'title' => 'Check No',
                    'dataIndex' => 'check_no',
                    'key' => 'check_no',
                ],
                [
                    'title' => 'Amount',
                    'dataIndex' => 'check_amount',
                    'key' => 'check_amount',
                ],
                [
                    'title' => 'Type',
                    'key' => 'type',
                    'dataIndex' => 'type',
                ],
                [
                    'title' => 'Category',
                    'dataIndex' => 'check_category',
                    'key' => 'c_cat',

                ],
                [
                    'title' => 'Select',
                    'key' => 'select',
                    'align' => 'center',
                ],
            ];

        }

        $totalAmount = $ds_checks_table->where('done', 'check');

        $ds_checks_table->transform(function ($item) {
            $item->done = $item->done === "" ? false : true;
            return $item;
        });

        return Inertia::render('Ds&BounceTagging/DsTagging', [
            'due_dates' => $due_dates,
            'total' => [
                'totalSum' => $totalAmount->sum(function ($item) {
                    return (float) str_replace(',', '', $item->check_amount);
                }),
                'count' => $totalAmount->count()

            ],
            'ds_c_table' => $ds_checks_table,
            'columns' => $columns,
            'type' => $type,
            'pagination' => [
                'current' => $ds_checks_table->currentPage(),
                'total' => $ds_checks_table->total(),
                'pageSize' => $ds_checks_table->perPage(),
            ],
        ]);
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
            ->where('checks.check_no', 'like', '%' . $request->search . '%')
            ->orderBy('new_ds_checks.date_time', 'desc')
            ->orderBy('checks.check_received', 'desc');


        $q = match ($request->dt_year) {
            $request->dt_year => $q->whereYear('checks.check_received', $request->dt_year),
            default => $q
        };

        $data = $q->paginate(20)->withQueryString();

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
        Checks::where('checks_id', $request->check_id)->update(['check_status' => 'BOUNCE']);

        SavedCheck::where('checks_id', $request->check_id)->update(['status' => 'BOUNCED']);

        $checkRec = new CheckHistory();
        $bounce_checks = new BouncedCheck();

        $checkhist = [
            'checks_id' => $request->check_id,
            'status' => 'bounce',
            'date_time' => $request->date,
            'user' => Auth::user()->id
        ];

        $checkhist1 = [
            'checks_id' => $request->check_id,
            'check_type' => 'bounce',
            'status' => '',
            'date_time' => $request->date,
            'user' => Auth::user()->id
        ];

        $checkRec->create($checkhist);
        $bounce_checks->create($checkhist1);

    }

    public function dummy()
    {
        $dummy = Checks::with('checkreceived')->get();
    }

    public function submiCheckDs(Request $request)
    {
        foreach ($request->selected as $check) {
            DB::transaction(function () use ($check, $request) {
                SavedCheck::where('checks_id', $check['checks_id'])->update(['ds_status' => 'remitted']);
                DsNumber::create([
                    'checks_id' => $check['checks_id'],
                    'ds_no' => $request->dsNo,
                    'date_deposit' => $request->dateDeposit,
                    'user' => $request->user()->id,
                    'status' => '',
                    'date_time' => today()
                ]);

            });
        }
        return redirect()->back();
    }

}
