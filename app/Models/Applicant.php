<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    protected $connection = 'pis';

    protected $table = 'applicant';

   
    public function employee3(){
        return $this->belongsTo(Employee3::class, 'emp_id', 'app_id');
    }
}
