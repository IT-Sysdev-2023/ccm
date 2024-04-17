<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Applicant;

use Illuminate\Contracts\Database\Eloquent\Builder;

class Employee3 extends Model
{
    use HasFactory;
    protected $connection = 'pis';

    protected $table = 'employee3';

    public function user(){
        return $this->belongsTo(User::class, 'empid', 'emp_no');
    }
    public function applicant()
    {
        return $this->hasOne(Applicant::class, 'app_id', 'emp_id');
    }

    
}
