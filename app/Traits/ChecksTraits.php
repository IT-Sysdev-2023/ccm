<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ChecksTraits
{
    public function scopeFindChecks(Builder $builder, $id): Builder
    {
        return $builder->where('checks_id', $id);
    }

    public function scopeJoinCheckRecCustomerDepartmentBanks(Builder $query): Builder
    {
        return $query->join('checksreceivingtransaction', 'checksreceivingtransaction.checksreceivingtransaction_id', '=', 'checks.checksreceivingtransaction_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id');
    }

    public function scopeWhereFilter(Builder $builder, $filter): Builder
    {
        return $builder->whereAny(['check_no', 'check_amount', 'customers.fullname'], 'like', '%' . $filter . '%');
    }

    public function scopeWhereDateChecks($query, $date): Builder
    {
        return $query->where(function ($query) use ($date) {
            if ($date !== null) {
                $query->whereDate('checks.date_time', $date);
            } else {
                $query->whereDate('checks.date_time', now()->toDateString());
            }
        });
    }

    public function scopeWhereDateTimeNotZero(Builder $builder): Builder
    {
        return $builder->where('date_time', '!=', '0000-00-00');
    }

    public function scopeSelectFilter($query)
    {
       return $query->select(
            'checks_id',
            'customer_id',
            'businessunit_id',
            'department_from',
            'check_no',
            'check_class',
            'check_category',
            'check_date',
            'check_received',
            'check_type',
            'bank_id',
            'approving_officer',
            'check_amount',
            'check_status',
            'account_no',
            'account_name',
        );
    }
}
