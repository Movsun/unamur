<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyAttribute extends Model
{
    protected $fillable = [
    	'name', 'operation'
    ];
    public $timestamps = false;
}
