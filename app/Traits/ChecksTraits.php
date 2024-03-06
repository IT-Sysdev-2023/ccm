<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ChecksTraits{
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

    public function scopeWhereCheckNo(Builder $builder, $filter): Builder
    {
        return $builder->where('check_no', 'like', '%' . $filter . '%');
    }

    public function scopeWhereDateChecks(Builder $builder, $date): Builder
    {
        return $builder->whereDate('checks.date_time', $date);
    }

    public function scopeWhereDateTimeNotZero(Builder $builder): Builder
    {
        return $builder->where('date_time', '!=', '0000-00-00');
    }
}