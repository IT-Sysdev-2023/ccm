<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewSavedChecks extends Model
{
    use HasFactory;
    protected $table = 'new_saved_checks';
    public $timestamps = false;
    protected $guarded = [];

    public function scopeDsTaggingQuery(Builder $query, int $id): Builder
    {
        return $query->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('new_saved_checks.status', '')
            ->where('checks.businessunit_id', $id)
            ->doesntHave('dsCheck.check');
    }

    public function scopeFindChecks(Builder $builder, int $id): Builder
    {
        return $builder->where('checks_id', $id);
    }

    public function scopeJoinChecksCustomer(Builder $builder): Builder
    {
        return $builder->join('checks', 'new_saved_checks.checks_id', '=', 'checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id');
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
        // dd(gettype($filters['status']));
        return $query->join('checks', 'checks.checks_id', '=', 'new_saved_checks.checks_id')
            ->join('customers', 'checks.customer_id', '=', 'customers.customer_id')
            ->where('checks.businessunit_id', $id)
            ->where('new_saved_checks.status', '=', "")
            ->when(!empty($filters) ?? null, function ($query, ) use ($filters) {
                $query->whereBetween('checks.check_received', [$filters['date_from'], $filters['date_to']]);
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                if ($status === '1') {
                    $query->where('checks.check_date', '<=', DB::raw('check_received'));
                } elseif ($status === '2') {
                    $query->where('checks.check_date', '>', DB::raw('check_received'));
                }
            });
    }

    public function check()
    {
        return $this->hasOne(Checks::class, 'checks_id', 'checks_id');
    }
    public function dsCheck()
    {
        return $this->hasOne(NewDsChecks::class, 'checks_id', 'checks_id');
    }
    public function employee()
    {
        // return $this->belongsTo(Employee3::class, 'checks_id', 'checks_id');
    }
}
