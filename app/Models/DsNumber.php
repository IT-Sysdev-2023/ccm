<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsNumber extends Model
{
    use HasFactory;

    protected $table = 'new_ds_checks';
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function check()
    {
        return $this->belongsTo(Checks::class, 'checks_id', 'checks_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user', 'id');
    }
}
