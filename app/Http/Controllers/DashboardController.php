<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helper\ColumnsHelper;

class DashboardController extends Controller
{
    public function adminDashboardComponent()
    {
        $users = User::select('name', 'username', 'usertype_id')->orderBy('created_at', 'DESC')->get();

        $users->transform(function ($item) {
            if($item->usertype_id == '1'){
                $item->usertype = 'Admin';
            }elseif($item->usertype_id == '2'){
                $item->usertype = 'Accounting';
            }else{
                $item->usertype = 'Treasury';
            }
            return $item;
        });

        $counts = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->selectRaw('
        SUM(CASE WHEN checks.businessunit_id = 21 AND new_saved_checks.status = "" THEN 1 ELSE 0 END) AS plazaMarcelaCounts,
        SUM(CASE WHEN checks.businessunit_id = 4 AND new_saved_checks.status = "" THEN 1 ELSE 0 END) AS islandCityMall,
        SUM(CASE WHEN checks.businessunit_id = 2 AND new_saved_checks.status = "" THEN 1 ELSE 0 END) AS ascMain
    ')
            ->first();

        $plazaMarcelaCounts = $counts->plazaMarcelaCounts;
        $islandCityMall = $counts->islandCityMall;
        $ascMain = $counts->ascMain;

        $data = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->select(
                DB::raw('COUNT(*) as y'),
                'check_received as x',
                DB::raw('checks.businessunit_id')
            )
            ->whereIn('checks.businessunit_id', [21, 4, 2])
            ->where('new_saved_checks.status', '=', '')
            ->groupBy('x', 'checks.businessunit_id')
            ->orderBy('x', 'desc')
            ->get();

        $dataPlazaMarcela = $data->where('businessunit_id', 21)->take(7)->values();
        $dataIslandCityMall = $data->where('businessunit_id', 4)->take(7)->values();
        $dataAscMain = $data->where('businessunit_id', 2)->take(7)->values();

        return Inertia::render('AdminDashboard', [
            'pmCounts' => $plazaMarcelaCounts,
            'icm' => $islandCityMall,
            'asc' => $ascMain,
            'pmWeekly' => $dataPlazaMarcela,
            'icmWeekly' => $dataIslandCityMall,
            'ascMainWeekly' => $dataAscMain,
            'users' => $users,
            'columns' => ColumnsHelper::$user_dashboard_table,
        ]);
    }
    public function treasuryDashboardComponent()
    {

        $businessUnitId = Auth::user()->businessunit_id;


        $checksBaseQuery = Checks::where('checks.businessunit_id', '=', $businessUnitId)
            ->join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->where('new_saved_checks.status', '=', '');

        $checksCount = (clone $checksBaseQuery)->count();

        $pdcCount = (clone $checksBaseQuery)
            ->where('checks.check_date', '>', DB::raw('check_received'))
            ->count();

        $datedCount = (clone $checksBaseQuery)
            ->where('checks.check_date', '<=', DB::raw('check_received'))
            ->count();

        $depositedCount = Checks::join('new_ds_checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->where('new_ds_checks.status', '=', '')
            ->where('checks.businessunit_id', '=', $businessUnitId)
            ->count();

        $bouncedCount = Checks::join('new_bounce_check', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->where('checks.businessunit_id', '=', $businessUnitId)
            ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
            ->count();

        $replacementCount = Checks::join('new_check_replacement', 'new_check_replacement.checks_id', '=', 'checks.checks_id')
            ->where('new_check_replacement.status', '!=', '')
            ->where('checks.businessunit_id', '=', $businessUnitId)
            ->count();

        return Inertia::render('TreasuryDashboard', [
            'checkCount' => $checksCount,
            'pdcCount' => $pdcCount,
            'datedCount' => $datedCount,
            'depositedCount' => $depositedCount,
            'bouncedCount' => $bouncedCount,
            'replacementCount' => $replacementCount,
        ]);
    }

    public function accountingDashboard()
    {
        $businessUnitId = Auth::user()->businessunit_id;

        $checksBaseQuery = Checks::where('checks.businessunit_id', '=', $businessUnitId)
            ->join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->where('new_saved_checks.status', '=', '');

        $checksCount = (clone $checksBaseQuery)->count();

        $pdcCount = (clone $checksBaseQuery)
            ->where('checks.check_date', '>', DB::raw('check_received'))
            ->count();

        $datedCount = (clone $checksBaseQuery)
            ->where('checks.check_date', '<=', DB::raw('check_received'))
            ->count();

        $depositedCount = Checks::join('new_ds_checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->where('new_ds_checks.status', '=', '')
            ->where('checks.businessunit_id', '=', $businessUnitId)
            ->count();

        $bouncedCount = Checks::join('new_bounce_check', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->where('checks.businessunit_id', '=', $businessUnitId)
            ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
            ->count();

        $replacementCount = Checks::join('new_check_replacement', 'new_check_replacement.checks_id', '=', 'checks.checks_id')
            ->where('new_check_replacement.status', '!=', '')
            ->where('checks.businessunit_id', '=', $businessUnitId)
            ->count();

        return Inertia::render('AccountingDashboard', [
            'checkCount' => $checksCount,
            'pdcCount' => $pdcCount,
            'datedCount' => $datedCount,
            'depositedCount' => $depositedCount,
            'bouncedCount' => $bouncedCount,
            'replacementCount' => $replacementCount,
        ]);
    }
}
