<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $guarded = [];

    public function check()
    {
        return $this->hasMany('App\Models\Check', 'customer_id', 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id', 'id');
    }
}
