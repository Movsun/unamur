<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'description'];

    public function devices(){
    	return $this->belongsToMany('App\Device');
    }
    public function user(){
	return $this->belongsTo('App\User');
    }
}
