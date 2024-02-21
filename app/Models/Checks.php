<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Checks extends Model
{
    use HasFactory;

    protected $table = 'checks';
    protected $primaryKey = 'checks_id';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public function checkreceived()
    {
        return $this->belongsTo('App\Models\CheckReceived', 'checksreceivingtransaction_id', 'checksreceivingtransaction_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank', 'bank_id', 'bank_id');
    }

    public function checktaggingitem()
    {
        return $this->hasMany('App\Models\CheckTaggingItem', 'checktagginghdr_id', 'checktagginghdr_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'currency_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_from', 'department_id');
    }

    public function save_checks()
    {
        return $this->belongsTo('App\Models\Saved_checks', 'checks_id', 'checks_id');
    }
    public function Check_replacement()
    {
        return $this->belongsTo('App\Models\Check_replacement', 'checks_id', 'checks_id');
    }
    public function dsChecks()
    {
        return $this->belongsTo(DsNumber::class, 'checks_id');
    }
}
