<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewBounceCheck extends Model
{
    use HasFactory;
    protected $table = 'new_bounce_check';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
