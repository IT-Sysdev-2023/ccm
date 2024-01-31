<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $table = 'businessunit';
    protected $primaryKey = 'businessunit_id';

    public function user()
    {
        return $this->belongsTo('App\Models\User','businessunit_id','businessunit_id');
    }
}
