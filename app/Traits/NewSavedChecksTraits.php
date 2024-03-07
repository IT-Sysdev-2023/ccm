<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait NewSavedChecksTraits
{
    public function scopeFindChecks(Builder $builder, int $id): Builder
    {
        return $builder->where('checks_id', $id);
    }

    public function scopeJoinChecksCustomer(Builder $builder): Builder
    {
        return $builder->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id');
    }
    public function scopeDsTaggingQuery(Builder $query, int $id): Builder
    {
        return $query->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('new_saved_checks.status', '')
            ->where('checks.businessunit_id', $id)
            ->doesntHave('dsCheck.check');
    }

    public function scopeJoinChecksCustomerBanksDepartment(Builder $builder): Builder
    {
        return $builder->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->join('banks', 'checks.bank_id', '=', 'banks.bank_id')
            ->join('department', 'department.department_id', '=', 'checks.department_from');
    }

    public function scopeEmptyStatusNoCheckWhereBu(Builder $builder, $id): Builder
    {
        return $builder->where('new_saved_checks.status', "")
            ->doesntHave('dsCheck.check')
            ->where('checks.businessunit_id', $id);
    }

    public function scopeReportQuery(Builder $builder, $bu, $filter): Builder
    {
        return $builder->where('businessunit_id', $bu)
            ->where('checks.check_no', 'like', '%' . $filter . '%');
    }

    public function scopeFilter($query, array $filters, $id)
    {

        return $query->join('checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('checks.businessunit_id', $id)
            ->where('new_saved_checks.status', '=', "")
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->when($filters['status'] ?? null, function ($query, $status) {
                if ($status === '1') {
                    $query->where('checks.check_date', '<=', DB::raw('check_received'));
                } elseif ($status === '2') {
                    $query->where('checks.check_date', '>', DB::raw('check_received'));
                }
            });

    }
}