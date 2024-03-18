<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currency';
    protected $primaryKey = 'currency_id';
    public $timestamp = false;
    protected $guarded = [];

    public function check()
    {
        return $this->hasMany('App\Models\Checks', 'currency_id', 'currency_id');
    }
}
