<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeJoinChecksCustomer(Builder $builder): Builder{
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
