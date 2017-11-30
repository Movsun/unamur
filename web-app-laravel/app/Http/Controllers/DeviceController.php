<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use Illuminate\Support\Facades\Session;
use App\UserProfile;
use Auth;
use App\LoraActivation;

class DeviceController extends Controller

{

	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	return view('device.index');
    }

    public function add(){
    	return view('device.add');
    }

    public function store(Request $request){

        if(Device::where('device_eui', $request['device_eui'])->first() != null){
            Session::flash('flash_message', 'Device eui already existed!');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();
        }

    	$device = Device::create($request->all());
        $userId = Auth::user()->id;
        $userProfile = UserProfile::where('user_id', $userId)->first();
        
        $userProfile->devices()->attach($device);

        Session::flash('flash_message', 'Device successfully stored!');
        Session::flash('flash_type', 'alert-success');
    	
        return redirect()->back();
    }

    public function edit()
    {   
        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();

        $devices = $userProfile->devices()->get();
        
        return view('device.edit', compact('devices'));
    }

    public function update(Request $request)
    {
        $device = Device::where('id', $request['device_eui'])->first();
        $device->update([
            'device_name' => $request['device_name'],
            'device_type' => $request['device_type'],
            'device_version' => $request['device_version'],
            'device_description' => $request['device_description'],
            ]);

        Session::flash('flash_message', 'Device successfully updated!');
        Session::flash('flash_type', 'alert-success');

        return redirect()->back();
    }

    public function remove()
    {
        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $devices = $userProfile->devices()->get();

        return view('device.remove', compact('devices'));
    }

    public function delete(Request $request)
    {
        $device = Device::where('id', $request['device_eui'])->first();
        $device->delete();

        Session::flash('flash_message', 'Device successfully removed!');
        Session::flash('flash_type', 'alert-success');

        return redirect()->back();
    }

    public function show(){
        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $devices = $userProfile->devices()->get();

        return view('device.show', compact('devices'));
    }

    public function setting(){
        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $lora = LoraActivation::first();
        $devices = $userProfile->devices()->with('loraActivation')->get();
        return view('device.setting', compact('devices'));
    }

    public function updateSetting(Request $request){
        $lora = LoraActivation::first();
        // todo: validate app key, app eui unqiue in database
        // todo: validate request
        $dev_id = $request['device_eui'];
        $device = Device::where('id', $dev_id)->first();
        if ($device == null) {
            return null;
        }

        $device_description = $request['device_description'];
        $dev_eui = $device['device_eui'];
        $dev_name = $device['device_name'];
        // dd($dev_eui);
        
        if($device->loraActivation()->first() == null){

            if ($device_description == 'LoRa') {
            $activation = $request['device_activation'];
            // dd($activation);
            if ($activation == 'otaa') {
                $app_eui = $request['app_eui'];
                $app_key = $request['app_key'];

                // dd('otaa');
                if ($app_eui == '' && $app_key == '') {
                     // todo: generate app_eui & key
                    // dd('empty auto generate');
                    $app_eui = substr(md5(rand()), 0, 16);
                    $app_key = substr(md5(rand()), 0, 32);
                } else {
                    $jsonBody = $this->getNodeBody(false, $app_eui, $app_key, $dev_eui, str_replace(' ', '', $dev_name));
                    $res = $this->postNodeToLoraServer($jsonBody);

                    if ($res->getStatusCode() == 200) {
                        // store setting to db
                        LoraActivation::create([
                            'device_id' => $dev_id,
                            'lora_mode' => 'otaa',
                            'application_eui' => $app_eui,
                            'application_key' => $app_key    
                        ]);

                        Session::flash('flash_message', 'Device setting successfully saved!');
                        Session::flash('flash_type', 'alert-success');

                        return redirect()->back();
                    } else {
                        // dd(json_decode($res->getBody()));
                        Session::flash('flash_message', json_decode($res->getBody())->error);
                        Session::flash('flash_type', 'alert-danger');

                        return redirect()->back();
                    }  
                }
                // todo: send to lora server, check if ok. 
            }   

            if ($activation == 'abp') {
                    $app_eui2 = $request['app_eui2'];
                    $dev_addr = $request['device_address'];
                    $netskey = $request['network_session_key'];
                    $appskey = $request['application_session_key'];

                    $jsonBody = $this->getNodeBody(true, $app_eui2, null, $dev_eui, $dev_name);
                    // dd($jsonBody);
                    $res = $this->postNodeToLoraServer($jsonBody);

                    if ($res->getStatusCode() == 200) {
                        // store setting to db
                        LoraActivation::create([
                            'device_id' => $dev_id,
                            'lora_mode' => 'abp',
                            'device_address' => $dev_addr,
                            'network_session_key' => $netskey,
                            'application_session_key' => $appskey    
                        ]);

                        $jsonBody2 = $this->getNodeActivationBody($dev_eui, $dev_addr, $netskey, $appskey);
                        // dd($jsonBody2);
                        $res2 = $this->postNodeActivationToLoraServer($jsonBody2, $dev_eui);
                        if ($res2->getStatusCode() == 200) {
                            Session::flash('flash_message', 'Device setting successfully activated!');
                            Session::flash('flash_type', 'alert-success');
                            return redirect()->back();
                        } else {
                            dd(json_decode($res2->getBody()));
                            Session::flash('flash_message', 'Device Existed or Other User already used this Device EUI!');
                            Session::flash('flash_type', 'alert-danger');

                            return redirect()->back();
                        }

                        
                    } else {
                        // dd(json_decode($res->getBody()));
                        Session::flash('flash_message', 'Device Existed or Other User already used this Device EUI!');
                        Session::flash('flash_type', 'alert-danger');

                        return redirect()->back();
                    }
            }

            }

            Session::flash('flash_message', 'Device setting successfully saved!');
            Session::flash('flash_type', 'alert-success');

            return redirect()->back();
        } else {

            if ($device_description == 'LoRa') {
            $activation = $request['device_activation'];
            // dd($activation);
            if ($activation == 'otaa') {
                $app_eui = $request['app_eui'];
                $app_key = $request['app_key'];

                // dd('otaa');
                if ($app_eui == '' && $app_key == '') {
                     // todo: generate app_eui & key
                    // dd('empty auto generate');
                    $app_eui = substr(md5(rand()), 0, 16);
                    $app_key = substr(md5(rand()), 0, 32);
                } else {
                    $jsonBody = $this->getNodeBody(false, $app_eui, $app_key, $dev_eui, str_replace(' ', '', $dev_name));
                    $res = $this->updatePostNodeToLoraServer($jsonBody, $dev_eui);

                    if ($res->getStatusCode() == 200) {
                        // store setting to db
                        $device->loraActivation()->first()->update([
                            'device_id' => $dev_id,
                            'lora_mode' => 'otaa',
                            'application_eui' => $app_eui,
                            'application_key' => $app_key    
                        ]);

                        Session::flash('flash_message', 'Device setting successfully saved!');
                        Session::flash('flash_type', 'alert-success');

                        return redirect()->back();
                    } else {
                        // dd(json_decode($res->getBody()));
                        Session::flash('flash_message', json_decode($res->getBody())->error);
                        Session::flash('flash_type', 'alert-danger');

                        return redirect()->back();
                    }  
                }
                // todo: send to lora server, check if ok. 
            }   

            if ($activation == 'abp') {
                    $app_eui2 = $request['app_eui2'];
                    $dev_addr = $request['device_address'];
                    $netskey = $request['network_session_key'];
                    $appskey = $request['application_session_key'];

                    $jsonBody = $this->getNodeBody(true, $app_eui2, null, $dev_eui, $dev_name);
                    // dd($jsonBody);
                    $res = $this->updatePostNodeToLoraServer($jsonBody, $dev_eui);

                    if ($res->getStatusCode() == 200) {
                        // store setting to db
                        $device->loraActivation()->first()->update([
                            'device_id' => $dev_id,
                            'lora_mode' => 'abp',
                            'device_address' => $dev_addr,
                            'network_session_key' => $netskey,
                            'application_session_key' => $appskey    
                        ]);

                        $jsonBody2 = $this->getNodeActivationBody($dev_eui, $dev_addr, $netskey, $appskey);
                        // dd($jsonBody2);
                        $res2 = $this->postNodeActivationToLoraServer($jsonBody2, $dev_eui);
                        if ($res2->getStatusCode() == 200) {
                            Session::flash('flash_message', 'Device setting successfully activated!');
                            Session::flash('flash_type', 'alert-success');
                            return redirect()->back();
                        } else {
                            // dd(json_decode($res2->getBody()));
                            Session::flash('flash_message', 'Device Existed or Other User already used this Device EUI!');
                            Session::flash('flash_type', 'alert-danger');

                            return redirect()->back();
                        }

                        
                    } else {
                        // dd(json_decode($res->getBody()));
                        Session::flash('flash_message', 'Device Existed or Other User already used this Device EUI!');
                        Session::flash('flash_type', 'alert-danger');

                        return redirect()->back();
                    }
            }

            }

            Session::flash('flash_message', 'Device setting successfully saved!');
            Session::flash('flash_type', 'alert-success');

            return redirect()->back();
            
        }

        

    }


    private function getNodeBody($isAbp, $appEui, $appKey, $devEui, $devName){
        $body = [
                      "adrInterval"=> 0,
                      "appEUI"=> $appEui,
                      "appKey"=> ($appKey == null)? $appEui.$devEui: $appKey,
                      "applicationID"=> getenv('LORASERVER_APP_ID') ?: '1',
                      // "channelListID"=> "",
                      //"description"=> "",
                      "devEUI"=> $devEui,
                      "installationMargin"=> 0,
                      "isABP"=> $isAbp,
                      "isClassC"=> false,
                      "name"=> $devName,
                      "relaxFCnt"=> true,
                      "rx1DROffset"=> 0,
                      "rx2DR"=> 0,
                      "rxDelay"=> 0,
                      "rxWindow"=> "RX1",
                      "useApplicationSettings"=> false
        ]; 
        
        return json_encode($body);
    }

    private function postNodeToLoraServer($jsonBody){
        $res = sendLoraServerApiRequest('POST', '/api/nodes', $jsonBody);
        return $res;
    }
    private function updatePostNodeToLoraServer($jsonBody, $deviceEui){
        $res = sendLoraServerApiRequest('PUT', '/api/nodes/'.$deviceEui, $jsonBody);
        return $res;
    }


    private function getNodeActivationBody($devEui, $devAddr, $appSKey, $netSKey){
        $body  = [
            "appSKey"=> $appSKey,
            "devAddr"=> $devAddr,
            "devEUI"=> $devEui,
            "fCntDown"=> 0,
            "fCntUp"=> 0,
            "nwkSKey"=> $netSKey
        ];
        return json_encode($body);
    }

    private function postNodeActivationToLoraServer($jsonBody, $devEui){
        $res = sendLoraServerApiRequest('POST', '/api/nodes/'.$devEui.'/activation', $jsonBody);
        return $res;
    }

    public function removeSetting(Request $request){
        $lora = LoraActivation::first();
        
        $device = Device::where('id', $request['device_id'])->first();
        if($device){
            $devEui = $device['device_eui'];
            $res = sendLoraServerApiRequest('Delete', '/api/nodes/'.$devEui, json_encode([]));
            
            if($res->getStatuscode() == 200){
                $device->loraActivation()->delete();
                Session::flash('flash_message', 'Device setting successfully remove!');
                Session::flash('flash_type', 'alert-success');
            } else if ($res->getStatuscode() == 404){
                $device->loraActivation()->delete();
                Session::flash('flash_message', 'Device setting does not exist!');
                Session::flash('flash_type', 'alert-danger');
            } else {
                // dd(json_decode($res->getBody()));
                Session::flash('flash_message', json_decode($res->getBody())->error);
                Session::flash('flash_type', 'alert-danger');
            }
        } else {
            Session::flash('flash_message', 'Device setting not found!');
            Session::flash('flash_type', 'alert-danger');
        }
        return redirect()->back();

    }
}



