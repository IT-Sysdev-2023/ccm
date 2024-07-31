<?php

namespace App\Services;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Models\Checks;
use App\Models\Currency;
use App\Models\NewSavedChecks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class DatedPdcCheckServices
{
    public function getPostDatedChecks(Request $request)
    {
        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->whereSearchFilter($request)
            ->whereColumn('checks.check_date', '>', 'checks.check_received')->get();

        $currency = Currency::orderBy('currency_name')->get();

        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();

        $class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

        $data->transform(function ($value) {

            $value->check_received = Date::parse($value->check_received)->toFormattedDateString();
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);

            return $value;
        });

        return (object) [
            'records' => $data,
            'currency' => $currency,
            'category' => $category,
            'check_class' => $class,
            'columns' => ColumnsHelper::$pdc_check_columns,
        ];
    }
    public function getDatedCheckData($request)
    {
        $data = NewSavedChecks::joinChecksCustomerBanksDepartment()
            ->emptyStatusNoCheckWhereBu($request->user()->businessunit_id)
            ->WhereSearchFilter($request)
            ->selectFilterDated()
            ->whereColumn('check_date', '<=', 'check_received')->get();

        $data->transform(function ($value) {
            $value->check_date = Date::parse($value->check_date)->toFormattedDateString();
            $value->check_amount = NumberHelper::currency($value->check_amount);
            return $value;
        });

        return $data;
    }
}
