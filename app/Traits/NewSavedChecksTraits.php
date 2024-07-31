<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
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
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereColumn('checks.checks_id', 'new_ds_checks.checks_id');
            });
    }

    public function scopeJoinChecksCustomerBanksDepartments(Builder $builder)
    {
        return $builder->select(
            'checks.checks_id',
            'checks.check_no',
            'checks.check_date',
            'checks.check_amount',
            'checks.check_received',
             'department.department',
            'customers.fullname'
        )
            ->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('department', 'department.department_id', 'checks.department_from')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id');
    }
    public function scopeSelectFilterDated($query)
    {
        return $query->select(
            'fullname',
            'checks.checks_id',
            'check_no',
            'checks.check_date',
            'checks.check_amount',
        );
    }

    public function scopeEmptyStatusNoCheckWhereBu(Builder $builder, $id)
    {
        return $builder->where('new_saved_checks.status', "")
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('new_ds_checks')
                    ->whereRaw('checks.checks_id = new_ds_checks.checks_id');
            })
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

    public function scopeFilterDPdcReports($query, $dateRange, $buid)
    {

        return $query->join('checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('checks.businessunit_id', $buid)
            ->where('new_saved_checks.status', '=', "")
            ->join('department', 'department.department_id', '=', 'checks.department_from')
            ->join('banks', 'banks.bank_id', '=', 'checks.bank_id')
            ->where('check_date', '>', DB::raw('check_received'))
            ->where('check_date', '<=', today())
            ->where(function ($query) use ($dateRange) {
                if ($dateRange && $dateRange[0] != null) {
                    $query->whereBetween('checks.check_received', [$dateRange[0], $dateRange[1]]);
                } else {
                    $query;
                }
            });
    }
    public function scopeWhereSearchFilter($query, $request)
    {
        return $query->whereAny([
            'checks.check_no',
            'checks.check_amount',
            'customers.fullname',
        ], 'LIKE', '%' . $request->search . '%');
    }
    public function scopeSelectFilter($query)
    {
        return $query->select([
            'checks.checks_id',
            'check_date',
            'done',
            'done',
            'check_no',
            'check_received',
            'check_amount',
            'check_category',
            'fullname',
        ]);
    }
}
