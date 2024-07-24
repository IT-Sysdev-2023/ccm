<?php

namespace App\Models;

use App\Traits\NewSavedChecksTraits;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public function scopeWhereSearchFilter($query, $request)
    {
        return $query->whereAny([
            'checks.check_no',
            'checks.check_amount',
            'customers.fullname',
        ], 'LIKE', '%' . $request->search . '%');
    }
    public function scopeSelectFilter($query)
    {
        return $query->select([
            'checks.checks_id',
            'check_date',
            'done',
            'done',
            'check_no',
            'check_received',
            'check_amount',
            'check_category',
            'fullname',
        ]);
    }
}
