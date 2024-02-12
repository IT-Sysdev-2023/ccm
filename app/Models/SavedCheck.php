<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedCheck extends Model
{
    use HasFactory;
    protected $table = 'new_saved_checks';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];

    public function check()
    {
        return $this->belongsTo(Checks::class, 'checks_id', 'checks_id');
    }
    public function employee()
    {
        // return $this->belongsTo(Employee3::class, 'checks_id', 'checks_id');
    }
}
