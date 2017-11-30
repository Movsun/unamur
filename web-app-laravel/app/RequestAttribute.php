<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestAttribute extends Model
{
    protected $fillable = [
    	'name', 'operation'
    ];
    public $timestamps = false;
}
