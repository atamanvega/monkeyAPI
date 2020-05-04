<?php

namespace App;

use App\Customer;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
    const USER_VERIFIED = '1';
    const USER_NOT_VERIFIED = '0';

    const USER_ADMIN = '1';
    const USER_REGULAR = '0';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isVerified()
    {
        return $this->verified == User::USER_VERIFIED;
    }

    public function isAdmin()
    {
        return $this->admin == User::USER_ADMIN;
    }

    public static function genVerificationToken()
    {
        return Str::random(40);
    }

    public function createdCustomers()
    {
        return $this->hasMany(Customer::class, 'created_by_user_id', 'id');
    }

    public function modifiedCustomers()
    {
        return $this->hasMany(Customer::class, 'modified_by_user_id', 'id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }
}
