<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoraActivation extends Model
{
    public $timestamps = false;

    protected $fillable = [ 'device_eui', 'lora_mode', 'device_address', 'network_session_key',
    'application_session_key', 'application_eui', 'application_key', 'device_id'
    ];
}
