<?php

namespace App\Http\Controllers;

use App\Models\Checks;
use App\Models\User;
use App\Models\Employee3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use San103\Phpholidayapi\HolidayClient;
use San103\Phpholidayapi\HolidayClientLaravel;

class DashboardController extends Controller
{

    //  private function balanceTransactions($bankAccount)
    // {
    //     $data = Dtr::findBankAccount($bankAccount)->select('trans_date as x', 'balance_amount as y')
    //         ->orderBy('trans_date', 'desc')
    //         ->groupBy('trans_date')
    //         ->limit(15)
    //         ->get();

    //       $data =  Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
    //     ->where('checks.businessunit_id', '=', '21')
    //     ->where('new_saved_checks.status', '=', '')
    //     ->orderBy('check_received', 'desc')
    //     ->limit(7)->get();

    //     $endDate = Date: arse($data->first()->x); //defined as end Date cause of the order by desc in the $data query
    //     $startDate = Date: arse($data->last()->x);

    //     $dates = $startDate->toPeriod($endDate)->toArray();

    //     $filled_records = collect();

    //     foreach ($dates as $date) {
    //         $formatted_date = $date->format('Y-m-d');
    //         $record = $data->firstWhere('x', $formatted_date);

    //         if ($record) {
    //             $filled_records->push($record);
    //         } else {
    //             $filled_records->push(new Dtr(['x' => $formatted_date, 'y' => null])); // Thanks To Chat GPT  
    //         }
    //     }

    //     $record = $filled_records->transform(function ($item) {
    //         $item->y = $item->y ? (float) $item->y : null;
    //         $item->x = Date: arse($item->x)->format('d M');
    //         return $item;
    //     });

    //     return $record;
    // } 
    //
    public function adminDashboardComponent()
    {
        $plazaMarcelaCounts = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
        ->where('checks.businessunit_id', '=', '21')
        ->where('new_saved_checks.status', '=', '')
        ->count();

        $islandCityMall = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
        ->where('checks.businessunit_id', '=', '4')
        ->where('new_saved_checks.status', '=', '')
        ->count();

        $ascMain = Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
        ->where('checks.businessunit_id', '=', '2')
        ->where('new_saved_checks.status', '=', '')
        ->count();

        
        $dataPlazaMarcela =  Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
        ->select(DB::raw('COUNT(*) as y , check_received as x'))
        ->where('checks.businessunit_id', '=', '21')
        ->where('new_saved_checks.status', '=', '')
        ->orderBy('x', 'desc')
        ->groupBy('x')
        ->limit(7)->get();

        $dataIslandCityMall =  Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
        ->select(DB::raw('COUNT(*) as y , check_received as x'))
        ->where('checks.businessunit_id', '=', '4')
        ->where('new_saved_checks.status', '=', '')
        ->orderBy('x', 'desc')
        ->groupBy('x')
        ->limit(7)->get();

        $dataAscMain =  Checks::join('new_saved_checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
        ->select(DB::raw('COUNT(*) as y , check_received as x'))
        ->where('checks.businessunit_id', '=', '2')
        ->where('new_saved_checks.status', '=', '')
        ->orderBy('x', 'desc')
        ->groupBy('x')
        ->limit(7)->get();

        // dd($data->toArray());


        return Inertia::render('AdminDashboard', [
            'pmCounts' => $plazaMarcelaCounts,
            'icm' => $islandCityMall,
            'asc' => $ascMain,
            'pmWeekly' => $dataPlazaMarcela,
            'icmWeekly' => $dataIslandCityMall,
            'ascMainWeekly' => $dataAscMain,
        ]);
    }
    public function treasuryDashboardComponent()
    {



        $holiday = new HolidayClientLaravel();

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
