<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $primaryKey = 'company_id';

    public function user()
    {
        return $this->belongsTo('App\Models\User','company_id','company_id');
    }
}
