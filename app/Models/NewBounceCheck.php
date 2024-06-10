<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\NewBounceTraits;

class NewBounceCheck extends Model
{
    use HasFactory;
    use NewBounceTraits;
    protected $table = 'new_bounce_check';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
