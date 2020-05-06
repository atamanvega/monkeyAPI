<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    protected $fillable = [
		'name',
		'surname',
		'id',
		'photo',
		'created_by_user_id',
		'modified_by_user_id',
    ];
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'customer_id';

    public function createdByUser()
    {
    	return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function modifiedByUser()
    {
    	return $this->belongsTo(User::class, 'modified_by_user_id');
    }
}
