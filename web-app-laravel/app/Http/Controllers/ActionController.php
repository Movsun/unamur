<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserProfile;
use App\Device;
use Auth;
use App\Event;
use Illuminate\Support\Facades\Session;
use App\Policy;
use App\RequestAttribute;


class ActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth')->except(['command', 'commandFromXacml']);
    }

    public function index(){
    	$userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
    	$events = Event::where('user_profile_id', $userProfile['id'])->get();
    	return view('action.list', compact('events'));
    }

    public function onDemand(){
    	$userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $devices = $userProfile->devices()->get();
    	return view('action.on-demand', compact('devices'));
    }

    public function createOnDemand(Request $request){
    	$devId = $request['device_id'];
    	$userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
    	$eventType = 'on-demand';
    	$interfaceModel = $request['interface_model'];

    	$event = Event::create([
    		'user_profile_id' => $userProfile['id'],
    		'type' => $eventType,
    		'interface_model' => $interfaceModel
    		]);
    	$device = Device::where('id', $devId)->first();
    	$event->devices()->attach($device);

    	Session::flash('flash_message', 'Event successfully stored!');
        Session::flash('flash_type', 'alert-success');
    	
    	return redirect()->back();
    }

    public function page($id){
    	$event = Event::where('id', $id)->first();
        $deviceId = $event->devices()->get()[0]['id'];
        $xml = $this->getRequest($deviceId);
        $appId = getenv("LORASERVER_APP_ID");
    	return view('action.page', compact('event', 'xml', 'appId'));
    }

    public function command(Request $request){
    	// dd($request['value']);

    	$deviceId = $request['device_id'];
    	$value = $request['value'];
    	$deviceEui = Device::where('id', $deviceId)->first()['device_eui'];
    	// dd($deviceId);
    	$body = [
			"confirmed"=> false,
			"data"=> base64_encode($value),
			"devEUI"=> $deviceEui,
			"fPort"=> 2,
			"reference"=> ""
    	];
    	// dd($body);
    	// for($i = 1; $i<6; $i++){
    		$res = sendLoraServerApiRequest('POST', '/api/nodes/'.$deviceEui.'/queue', json_encode($body));
    		// sleep(1);

    	// }
    	
    	dd(json_decode($res->getBody()));

    	// todo: publish mqtt command to /application/id/node/eui/ value
    }

    public function commandFromXacml(Request $request){
        $deviceEui = $request['device_eui'];
        $value = $request['value'];
        // $deviceEui = Device::where('device_eui', $device)->first()['device_eui'];
        // dd($deviceId);
        $body = [
            "confirmed"=> false,
            "data"=> base64_encode($value),
            "devEUI"=> $deviceEui,
            "fPort"=> 2,
            "reference"=> ""
        ];
        // file_put_contents(resource_path("test.txt"), json_encode($body));
        // dd($request->all());
        $res = sendLoraServerApiRequest('POST', '/api/nodes/'.$deviceEui.'/queue', json_encode($body));
        // file_put_contents(resource_path("test.txt"), $body);
        dd($res->getStatusCode());
        // dd(json_decode($res->getBody()));
        // todo: publish mqtt command to /application/id/node/eui/ value
    }

    public function automatic(){

        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $devices = $userProfile->devices()->get();
        return view('action.automatic', compact('devices'));
    }

    public function createAutomatic(Request $request){
        $devId = $request['actuator_id'];
        $sensorValue = $request['sensor_value'];
        $sensorId = $request['sensor_id'];
        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $eventType = 'automatic';
        $interfaceModel = 6;

        $event = Event::create([
            'user_profile_id' => $userProfile['id'],
            'type' => $eventType,
            'interface_model' => $interfaceModel
            ]);
        $actuator = Device::where('id', $devId)->first();
        $sensor = Device::where('id', $sensorId)->first();
        $event->devices()->attach($actuator, [
             'type' => 'Actuator',
             'value' => '' 
            ]);
        $event->devices()->attach($sensor, [
             'type' => 'Sensor',
             'value' => $sensorValue 
            ]);
        

        Session::flash('flash_message', 'Event successfully stored!');
        Session::flash('flash_type', 'alert-success');
        
        return redirect()->back();
    }


    // it mays be faster if all the request are created and store instead of generate the same every time
    private function getRequest($deviceId){
        $actions = ['TurnOn', 'TurnOff', 'Get'];
        $xmls = [];
        foreach($actions as $action){
            $tmp = '';
            $policy = Policy::where([['device_id', $deviceId], ['subject_id', Auth()->user()->userProfile()->first()->id], ['action', $action]])->first();
            if ($policy) {
                $device = Device::where('id', $deviceId)->first();
                $request = json_decode($policy['request']);
                $tmp = file_get_contents(resource_path('policy/sample/request.xml'));
                $tmp = str_replace('{subject}', Auth::user()->username, $tmp);
                $tmp = str_replace('{resource}', $device['device_eui'], $tmp);
                $tmp = str_replace('{device-type}', $device['device_description'], $tmp);
                $env = '';
                
                foreach ($request->condition as $condition){
                    $rp = RequestAttribute::where('name', $condition->type)->first();
                    if ($rp) {
                        $tmp2 = $rp->operation;
                        $tmp2 = str_replace("{name}", $condition->name, $tmp2);
                        $tmp2 = str_replace("{data-type}", $condition->data_type, $tmp2);
                        $env = $env. $tmp2;
                    }
                }
                $tmp = str_replace('{environment}', $env, $tmp);
                $xmls[$action] = $tmp;
            }
        }
        

        return $xmls;
    }

    public function remove($id){
        $event = Event::where('id', $id)->first();
        $event->delete();
        Session::flash('flash_message', 'Event successfully removed!');
        Session::flash('flash_type', 'alert-success');
        return redirect()->back();
    }
}
