<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckRecieved extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'checksreceivingtransaction';
    protected $primaryKey = 'checksreceivingtransaction_id';

    public function user()
    {
        return $this->hasMany('App\Models\User', 'id', 'id');
    }

    public function salesman()
    {
        return $this->hasMany('App\Models\Salesman', 'salesman_id', 'salesman_id');
    }

    public function check()
    {
        return $this->hasMany('App\Models\Check', 'checksreceivingtransaction_id', 'checksreceivingtransaction_id');
    }

}
