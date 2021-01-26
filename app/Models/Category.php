<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
     use SoftDeletes;

      protected $fillable = [
        'user_id',
        'category_name',
    ];

    public function user(){

    	//return $this->belongsTo('App\Models\User');

    	return $this->hasOne(User::class, 'id', 'user_id');
    }
}
