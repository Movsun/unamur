<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'interface_model', 'user_profile_id', 'request_event'];

    public function devices(){
    	return $this->belongsToMany('App\Device');
    }
}
