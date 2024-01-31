<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'usertype';
    protected $primaryKey = 'usertype_id';

     public function user()
    {
        return $this->belongsTo('App\Models\User','usertype_id','usertype_id');
    }
}
