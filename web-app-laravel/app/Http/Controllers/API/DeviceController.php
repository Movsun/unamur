<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Device;
use App\UserProfile;
use Auth;
use Response;

class DeviceController extends Controller
{
    public function index(Request $request){

        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $devices = $userProfile->devices()->get();

        return $devices;
    }

    public function store(Request $request){

        $this->validate($request, [
            'device_description' => 'required',
            'device_name' => 'required',
            'device_version' => 'required',
            'device_type' => 'required'
        ]);
        $data = $request->all();
        if ($data['device_description'] == 'BLE' || $data['device_description'] == "WiFi"){
          $data['device_eui'] = "0000".str_replace(":", "", $data['device_mac']);
        } else if ($data['device_description'] == "LoRa"){
        } else {
          return Response::json(['error'=> "device_description must be on of the following: LoRa, BLE or WiFi"], 422);
        }

        if(Device::where('device_eui', $data['device_eui'])->first() != null){
            return Response::json(['error'=> 'object already exist!'], 409);
        }

        // store device and attache owner id
        $device = Device::create($data);
        $userId = Auth::user()->id;
        $userProfile = UserProfile::where('user_id', $userId)->first();
        $userProfile->devices()->attach($device);
        return $device;
    }

    public function show($id){
        $device = Device::where('id', $id)->first();
        if (!$device){
          return Response::json(['error'=> 'device not found'], 404);
        }
        if ($device->userProfiles()->first()->user()->first()->id != Auth::user()->id){
          return Response::json(['error'=> 'device belong to other user'], 401);
        }

        return $device;
    }

    public function update(Request $request, $id){
        $device = Device::where('id', $id)->first();
        if($device == null){
            return Response::json(['error'=> 'Device does not exist'], 404);
        }

        $this->validate($request, [
            'device_description' => 'required',
            'device_name' => 'required',
            'device_version' => 'required',
            'device_type' => 'required'
        ]);
        $data = $request->all();
        if ($data['device_description'] == 'BLE' || $data['device_description'] == "WiFi"){
          $data['device_eui'] = "0000".str_replace(":", "", $data['device_mac']);
        } else if ($data['device_description'] == "LoRa"){
        } else {
          return Response::json(['error'=> "device_description must be on of the following: LoRa, BLE or WiFi"], 422);
        }
        $other_dev = Device::where('device_eui', $data['device_eui'])->first();
        if($other_dev != null && $other_dev['id'] != $device['id']){
            return Response::json(['error'=> 'device eui already taken!'], 409);
        }

        if ($device->userProfiles()->first()->user()->first()->id != Auth::user()->id){
          return Response::json(['error'=> 'device belong to other user'], 401);
        }

        $device->update($data);
        return $device;

    }

    public function destroy($id){
      $device = Device::where('id', $id)->first();
      if($device == null){
          return Response::json(['error'=> 'Device does not exist'], 404);
      }
      if ($device->userProfiles()->first()->user()->first()->id != Auth::user()->id){
        return Response::json(['error'=> 'device belong to other user'], 401);
      }
      if(!$device->delete()){
        return Response::json(['error'=> 'device not deleted'], 500);
      }
      return Response::json("OK", 200);
    }

    public function uplink($id){
      $device = Device::where('id', $id)->first();
      if($device == null){
          return Response::json(['error'=> 'Device does not exist'], 404);
      }
      if ($device->userProfiles()->first()->user()->first()->id != Auth::user()->id){
        return Response::json(['error'=> 'device belong to other user'], 401);
      }

      $uplinkTopic = $device['uplink_topic'];
      if ($uplinkTopic){
        $message = Redis::get($uplinkTopic);
      } else {
        return Response::json(['error'=> 'Device Description not support'], 500);
      }
      if ($message == null){
        return Response::json(null, 204);
      } else {
        return json_decode($message, true);
      }

    }

    public function downlink(Request $request, $id){
        // publish mqtt downlink message
        $dev = Device::find($id);
        if ($dev){
          //todo: check if user has permission on device
          Redis::publish($dev['downlinkTopic'], json_encode($request->all()));
          return Response::json("OK", 200);
        } else {
          return Response::json("Not Found", 404);
        }


    }
}
