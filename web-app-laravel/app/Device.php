<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
	public $timestamps = false;
    protected $fillable = ['device_eui', 'device_name', 'device_description', 'device_version', 'device_type'];
		protected $hidden = ['pivot'];
		protected $appends = ['device_mac'];
		// protected $visible = ['id', 'device_name', 'device_description', 'device_version', 'device_type', 'device_eui', 'device_mac'];
    public function userProfiles(){
    	return $this->belongsToMany('App\UserProfile');
    }
    public function loraActivation(){
    	return $this->hasMany('App\loraActivation');
    }
    public function setDeviceEuiAttribute($value)
    {
        $this->attributes['device_eui'] = strtolower($value);
    }
    public function getDeviceEuiAttribute($value)
    {
        $desc = $this->attributes['device_description'];
				if ($desc == 'BLE' || $desc == 'WiFi'){
					return "";
				} else {
					return $value;
				}
    }

		public function getDeviceMacAttribute(){
				$desc = $this->attributes['device_description'];
				if ($desc == 'BLE' || $desc == 'WiFi'){
					return preg_replace('~..(?!$)~', '\0:', substr($this->attributes['device_eui'], 4));
				} else {
					return "";
				}
		}

		public function getUplinkTopicAttribute(){
			$desc = $this->attributes['device_description'];
			if ($desc == 'BLE'){
        return 'ble/'.$this->getDeviceMacAttribute().'/up';
      } else if ($desc == "WiFi"){
        return 'wifi/'.$this->getDeviceMacAttribute().'/up';
      } else if ($desc == 'LoRa'){
        return 'node/'.$this->attributes['device_eui'].'/up';
      } else {
        return null;
      }
		}
		public function getDownlinkTopicAttribute(){
			$desc = $this->attributes['device_description'];
			if ($desc == 'BLE'){
        return 'ble/'.$this->getDeviceMacAttribute().'/down';
      } else if ($desc == "WiFi"){
        return 'wifi/'.$this->getDeviceMacAttribute().'/down';
      } else if ($desc == 'LoRa'){
        return 'node/'.$this->attributes['device_eui'].'/down';
      } else {
        return null;
      }
		}
}
