<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCheckReplacement extends Model
{
    use HasFactory;
    protected $table = 'new_check_replacement';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function check()
    {
        return $this->belongsTo('App\Check', 'checks_id', 'checks_id');
    }
}
