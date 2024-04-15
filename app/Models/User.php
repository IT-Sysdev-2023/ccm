<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'username',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function company()
    {
        return $this->hasOne('App\Models\Company', 'company_id', 'company_id');
    }

    public function businessunit()
    {
        return $this->hasOne('App\Models\BusinessUnit', 'businessunit_id', 'businessunit_id');
    }

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'department_id', 'department_id');
    }

    public function usertype()
    {
        return $this->hasOne('App\Models\UserType', 'usertype_id', 'usertype_id');
    }

    public function salesman()
    {
        return $this->hasMany('App\Models\Salesman', 'id', 'id');
    }

    public function bank()
    {
        return $this->hasMany('App\Models\Bank', 'id', 'id');
    }

    public function customer()
    {
        return $this->hasMany('App\Models\Customer', 'id', 'id');
    }

    public function checktagging()
    {
        return $this->hasMany('App\Models\CheckTagging', 'id', 'id');
    }
    public function ds_checks()
    {
        return $this->hasMany('App\Models\Ds_number', 'id', 'user');
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Str::title(Str::lower($value)),
            set: fn($value) => Str::title(Str::lower($value)),
        );
    }
    public function department(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Str::title(Str::lower($value)),
            set: fn($value) => Str::title(Str::lower($value)),
        );
    }
    public function username(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Str::title(Str::lower($value)),
            set: fn($value) => Str::title(Str::lower($value)),
        );
    }
}
