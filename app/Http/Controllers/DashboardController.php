<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use San103\Phpholidayapi\HolidayClient;

class DashboardController extends Controller
{
    //
    public function adminDashboardComponent()
    {

    }
    public function treasuryDashboardComponent()
    {
        $holiday = new HolidayClient();

        $va = $holiday
            ->year((string) today()->year)
            ->result();

        $checksCount = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->where('checks.businessunit_id', '=', Auth::user()->businessunit_id)
            ->where('new_saved_checks.status', '=', '')
            ->count();

        $pdcCount = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->where('checks.check_date', '>', DB::raw('check_received'))
            ->where('checks.businessunit_id', '=', Auth::user()->businessunit_id)
            ->where('new_saved_checks.status', '=', '')
            ->count();

        $datedCount = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->where('checks.check_date', '<=', DB::raw('check_received'))
            ->where('checks.businessunit_id', '=', Auth::user()->businessunit_id)
            ->where('new_saved_checks.status', '=', '')
            ->count();


        $depositedCount = Checks::join('new_ds_checks', 'new_ds_checks.checks_id', '=', 'checks.checks_id')
            ->where('new_ds_checks.status', '=', '')
            ->where('checks.businessunit_id', '=', Auth::user()->businessunit_id)
            ->count();

        $bouncedCount = Checks::join('new_bounce_check', 'checks.checks_id', '=', 'new_bounce_check.checks_id')
            ->where('checks.businessunit_id', '=', Auth::user()->businessunit_id)
            ->whereIn('new_bounce_check.status', ['', 'PARTIAL'])
            ->count();

        $replacementCount = Checks::join('new_check_replacement', 'new_check_replacement.checks_id', '=', 'checks.checks_id')
            ->where('new_check_replacement.status', '!=', '')
            ->where('checks.businessunit_id', '=', Auth::user()->businessunit_id)
            ->count();


        return Inertia::render('TreasuryDashboard', [
            'holiday' => collect($va)->values(),
            'checkCount' => $checksCount,
            'pdcCount' => $pdcCount,
            'datedCount' => $datedCount,
            'depositedCount' => $depositedCount,
            'bouncedCount' => $bouncedCount,
            'replacementCount' => $replacementCount,
        ]);
    }
}
