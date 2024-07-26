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
            ->whereAny([
                'checks.check_no',
                'checks.check_amount',
                'customers.fullname'
            ], 'LIKE', '%' . $request->search . '%')
            ->whereColumn('checks.check_date', '>', 'checks.check_received')
            ->paginate(10)->withQueryString();

        $currency = Currency::orderBy('currency_name')->get();
        $category = Checks::select('check_category')->where('check_category', '!=', '')->groupBy('check_category')->get();
        $check_class = Checks::select('check_class')->where('check_class', '!=', '')->groupBy('check_class')->get();

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
            'check_class' => $check_class,
            'columns' => ColumnsHelper::$pdc_check_columns,
        ];

    }
}
