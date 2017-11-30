<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $fillable = ['client_id', 'username', 'password', 'name', 'network_ip', 'port_number', 'network_type_id', 'user_profile_id'];
}
