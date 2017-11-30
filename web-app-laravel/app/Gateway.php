<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $fillable = ['eui', 'name'];
    public $timestamps =false;
}
