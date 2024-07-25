<?php

namespace App\Models;

use App\Traits\NewDsCheckTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewDsChecks extends Model
{
    use NewDsCheckTraits;
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
        return $this->belongsTo(User::class, 'id', 'user');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'bank_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_from');
    }

}
