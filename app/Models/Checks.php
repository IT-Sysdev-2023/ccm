<?php

namespace App\Models;

use App\Helper\NumberHelper;
use App\Traits\ChecksTraits;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;


class Checks extends Model
{
    use HasFactory;
    use ChecksTraits;

    protected $guarded = [];
    protected $table = 'checks';

    protected $primaryKey = 'checks_id';
    public $timestamps = false;
    
    protected $dates = ['deleted_at'];

    protected $casts = [
        'is_exist' => 'boolean',
        'check_date' => 'date',
        'check_received' => 'date',
        'is_manual_entry' => 'boolean'

    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->toFormattedDateString();
    }

    public function checkAmount(): Attribute
    {
        return Attribute::make(
            get: fn(float $value) => NumberHelper::currency($value)
        );
    }

    public function checkreceived()
    {
        return $this->belongsTo(CheckRecieved::class, 'checksreceivingtransaction_id', 'checksreceivingtransaction_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Models\Bank', 'bank_id', 'bank_id');
    }

    public function checktaggingitem()
    {
        return $this->hasMany('App\Models\CheckTaggingItem', 'checktagginghdr_id', 'checktagginghdr_id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'currency_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_from', 'department_id');
    }

    public function save_checks()
    {
        return $this->belongsTo('App\Models\Saved_checks', 'checks_id', 'checks_id');
    }
    public function Check_replacement()
    {
        return $this->belongsTo('App\Models\Check_replacement', 'checks_id', 'checks_id');
    }
    public function dsChecks()
    {
        return $this->belongsTo(NewDsChecks::class, 'checks_id');
    }


}
