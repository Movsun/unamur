<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
  protected $fillable = ['eui', 'name'];
  public $timestamps =false;
}
