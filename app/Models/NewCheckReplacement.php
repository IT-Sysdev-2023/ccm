<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function check()
    {
        return $this->belongsTo('App\Check', 'checks_id', 'checks_id');
    }
}
