<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Builder;

class NewCheckReplacement extends Model
{
    use HasFactory;
    protected $table = 'new_check_replacement';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function scopeJoinCheckReplacementCustomer(Builder $builder): Builder{
        return $builder->join('checks', 'new_check_replacement.checks_id', '=', 'checks.checks_id')
        ->join('customers', 'checks.customer_id', '=', 'customers.customer_id');
    }

    public function scopeChecksMode(Builder $query, $id): Builder{
        return $query->where('checks_id', $id)
        ->where('mode', 'PARTIAL');
    }
    public function check()
    {
        return $this->belongsTo(Checks::class, 'checks_id', 'checks_id');
    }
}
