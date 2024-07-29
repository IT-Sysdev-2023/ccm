<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';
    protected $primaryKey = 'bank_id';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Molels\User', 'id', 'id');
    }
    public function check()
    {
        return $this->hasMany('App\Models\Check', 'bank_id', 'bank_id');
    }
}
