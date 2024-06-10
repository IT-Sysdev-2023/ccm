<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait NewBounceTraits
{
    public function scopeWhereFilter(Builder $builder, $filter): Builder
    {
        return $builder->whereAny(['checks.check_no', 'checks.check_amount', 'customers.fullname', 'banks.bankbranchname'], 'like', '%' . $filter . '%');
    }

}
