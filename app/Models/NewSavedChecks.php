<?php

namespace App\Models;

use App\Traits\NewSavedChecksTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class NewSavedChecks extends Model
{
    use NewSavedChecksTraits;

    use HasFactory;
    protected $table = 'new_saved_checks';
    public $timestamps = false;
    protected $guarded = [];

    public function check()
    {
        return $this->hasOne(Checks::class, 'checks_id', 'checks_id');
    }
    public function dsCheck()
    {
        return $this->hasOne(NewDsChecks::class, 'checks_id', 'checks_id');
    }
    public function employee()
    {
        // return $this->belongsTo(Employee3::class, 'checks_id', 'checks_id');
    }

}
