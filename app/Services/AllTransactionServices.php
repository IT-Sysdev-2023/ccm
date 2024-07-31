<?php

namespace App\Services;

use App\Models\Checks;
use App\Models\Currency;
use App\Models\NewSavedChecks;
use Illuminate\Support\Facades\Date;

class AllTransactionServices
{
    public static function getCurrencyCategClass()
    {
        $currency = Currency::orderBy('currency_name')->get();
        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();
        $check_class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

        return (object) [
            'currency' => $currency,
            'category' => $category,
            'check_class' => $check_class,
        ];
    }

    public function getManualEntryServices($request)
    {

        $data = NewSavedChecks::joinChecksCustomerBanksDepartments()
            ->where('checks.businessunit_id', $request->user()->businessunit_id)
            ->where('checks.is_manual_entry', true)
            ->where('new_saved_checks.status', '')
            ->whereSearchFilter($request)
            ->orderBy('checks.check_received')
            ->paginate(10)->withQueryString();

        $data->transform(function ($value) {
            $value->type = Date::parse($value->check_date)->lessThanOrEqualTo(today()) ? 'DATED' : 'POST DATED';
            return $value;
        });

        return (object) [
            'record' => $data,
            'select' => self::getCurrencyCategClass(),
        ];

    }
}

