<?php

namespace App\Traits;

trait NewDsCheckTraits
{
    public function scopeSelectFilter($query)
    {
        return $query->select(
            'new_ds_checks.checks_id',
            'ds_no',
        );
    }
    public function scopeSearchFilter($query, $request)
    {
        return $query->whereAny([
            'new_ds_checks.ds_no',
        ], 'LIKE', '%' . $request->search)
            ->orWhereHas('check', function ($query) use ($request) {
                $query->whereAny([
                    'check_no',
                    'check_amount'
                ], 'LIKE', '%' . $request->search . '%');
            });
    }
}
