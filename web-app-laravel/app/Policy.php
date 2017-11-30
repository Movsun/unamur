<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
    	'user_profile_id', 'device_id', 'type', 'request', 'description', 'condition_type', 'subject_id', 'action'
    ];
    public $timestamps = false;

    public function subject(){
    	return $this->belongsTo('App\User', 'subject_id');
    }
    public function resource(){
    	return $this->belongsTo('App\Device', 'device_id');
    }
}
