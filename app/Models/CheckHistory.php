<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckHistory extends Model
{
    use HasFactory;

    protected $table = 'new_check_history';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
}
