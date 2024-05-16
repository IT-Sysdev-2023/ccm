<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewDsChecks extends Model
{
      use HasFactory;

    protected $table = 'new_ds_checks';
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function check()
    {
        return $this->belongsTo(Checks::class, 'checks_id', 'checks_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user', 'id');
    }
    public function bank()
    {
        return $this->belongsTo('App\Models\Bank', 'bank_id', 'bank_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'customer_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id', 'department_from');
    }

}
