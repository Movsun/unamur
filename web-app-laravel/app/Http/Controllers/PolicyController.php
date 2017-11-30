<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Device;
use App\UserProfile;
use Auth;
use App\Policy;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\PolicyAttribute;

class PolicyController extends Controller
{

	public function __construct(){
		$this->middleware('auth');
	}

    public function add(){
    	//$users = User::all();
        $users = User::where('id', Auth::user()->id)->get();
    	$devices = UserProfile::where('user_id', Auth::user()['id'])->first()->devices()->get();
        $policyAttributes = PolicyAttribute::all();
    	return view('policy.add', compact('users', 'devices', 'policyAttributes'));
    }

    public function show(){
        $policies = Policy::where('user_profile_id', Auth::user()->userProfile()->first()['id'])->get();
    	return view('policy.show', compact('policies'));
    }

    public function store(Request $request){
        // dd($request);
        $p = Policy::where([['device_id', $request['resource']], ['subject_id', $request['subject']], ['action', $request['action']]])->first();
        if($p){
            Session::flash('flash_message', 'Policy Subject, Action and Resource Already Defined');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();
        }
	$userId = UserProfile::where('id', $request['subject'])->first()->user()->first()->id;

    	$tmp = [
    		'subject' => User::where('id', $userId)->first()['username'],
    		'action' => $request['action'],
    		'resource' => Device::where('id', $request['resource'])->first()['device_eui'],
    		'operation' => $request['condition_type'],
    		'policy-description' => $request['policy_description']
    	];
    	
    	$conditions = [];
    	for($i =0; $i< count($request['eui_name']); $i++){
            //dd($request['eui_name'][$i]);
            if ($request['eui_value'][$i] == null || $request['eui_value'][$i] == ''){
                continue;
            }
            $subVal = substr($request['eui_value'][$i], 1, strlen($request['eui_value'][$i])-1);
            if (is_numeric($subVal)){
                $tmp2 = 'double';
                // dd($tmp);
            } else {
                $tmp2 = 'string';
            }

            $array = [
                'name' => Device::where('id', $request['eui_name'][$i])->first()['device_eui'],
                'value' => $request['eui_value'][$i],
                'type' => 'Sensor',
                'data_type' => $tmp2
            ];
            $array = $this->pushFunctionTypeOfCondition($array);
    		array_push($conditions, $array);
    	}
    	for($i =0; $i< count($request['env_type']); $i++){
            if ($request['env_value'][$i] == null || $request['env_value'][$i] == ''){
                continue;
            }
            $array = [
                'name' => $request['env_type'][$i],
                'value' => $request['env_value'][$i],
                'type' => $request['env_type'][$i],
                'data_type' => strtolower($request['env_type'][$i])
             ];
            $array = $this->pushFunctionTypeOfCondition($array);
    		array_push($conditions, $array);
    	}

    	$tmp['condition'] = $conditions;
 
    	$userProfile = UserProfile::where('user_id', Auth::user()['id'])->first();

        $subjectId = $request['subject'];

        // if policy already exist
	$new = Policy::where([['device_id', $request['resource']], ['subject_id', $subjectId], ['action', $request['action']]])->first();
        if($new != null){
            $new->update([
                'user_profile_id' => $userProfile['id'],
                'request' => json_encode($tmp),
                'type' => $request['policy_type'],
                'device_id' => $request['resource'],
                'condition_type' => $request['condition_type'],
                'description' => $request['policy_description'],
                'subject_id' => $subjectId,
                'action' => $request['action']
            ]);
        } else {
            $new = Policy::create([
                'user_profile_id' => $userProfile['id'],
                'request' => json_encode($tmp),
                'type' => $request['policy_type'],
                'device_id' => $request['resource'],
                'condition_type' => $request['condition_type'],
                'description' => $request['policy_description'],
                'subject_id' => $subjectId,
                'action' => $request['action']
            ]);
        }

    	if(!$this->createPolicyFile($new['id'], $tmp, $conditions)){
    		Session::flash('flash_message', 'Fail to create policy file');
        	Session::flash('flash_type', 'alert-danger');
        	// todo: delete policy from database
    		return redirect()->back();
    	}

    	Session::flash('flash_message', 'Policy successfully stored!');
        Session::flash('flash_type', 'alert-success');

    	return redirect()->back();

    }

    private function createPolicyFile($id, $policy){
        $content = file_get_contents(resource_path('policy/sample/policy.xml'));
        $content = str_replace('{policy-id}', 'policy-'. $id, $content);

    	$tmp = '';
		foreach($policy['condition'] as $condition){
			
            $xml = '';
                $p = PolicyAttribute::where('name', $condition['type'])->first();
                if ($p){
                    switch($p->name){
                        case 'Date' : 
                            $xml = json_decode($p->operation)[0]->xml;
                            $values = explode('-', $condition['value']);
                            $c1 = Carbon::parse($values[0]);
                            $c2 = Carbon::parse($values[1]);
                            $xml = str_replace('{1st-value}', $c1->format('Y-m-d'), $xml);
                            $xml = str_replace('{2nd-value}', $c2->format('Y-m-d'), $xml);
                            break;
                        case 'Sensor':
                            $value = $condition['value'];
                            // dd($value);
                            $subVal = substr($value, 1, strlen($value)-1);
                            if (is_numeric($subVal)){

                                $tmp2 = $value[0]. 'float';
                                // dd($tmp);
                            } else {
                                $tmp2 = '=string';
                            }
                            foreach(json_decode($p->operation) as $x){
                                if ($x->name == $tmp2){
                                    $xml = $x->xml;
                                    // dd($xml);
                                    $xml = str_replace('{value}', $subVal, $xml);
                                    // dd($subVal);
                                    $xml = str_replace('{name}', $condition['name'], $xml);
                                    // dd($xml);
                                }
                            }

                            break;
                        case 'Time' : 
                            $xml = json_decode($p->operation)[0]->xml;
                            $values = explode('-', $condition['value']);
                            $xml = str_replace('{1st-value}', $values[0], $xml);
                            $xml = str_replace('{2nd-value}', $values[1], $xml);
                            break;
                        case 'Location' :
                        case 'Device IP Address':
                        case 'User ID':
                        case 'Risk Acessment/History':
                        case 'Risk Acessment/Location':
                            $xml = json_decode($p->operation)[0]->xml;
                            $values = $condition['value'];
                            $xml = str_replace('{value}', $values, $xml);
                            break;
                    }
                }
			// }
			$tmp = $tmp.$xml;
		}
		foreach($policy as $name => $value){
            if ($name == 'condition') {
                    continue;
            }   
			$content = str_replace('{'.$name.'}', $value, $content);
		}
		$content = str_replace('{condition}', $tmp, $content);
		file_put_contents(resource_path('policy/'.$id.'.xml'),$content);
		return true;
    }

    private function pushFunctionTypeOfCondition($array){
        // $value = $array['value'];
        // if ($value[0] == '=') {
        //     $array['value'] = str_replace('=', '', $value);
        //     $array['function1'] = $array['type'].'-one-and-only';
        //     $array['function2'] = $array['type'].'-equal';
        // } else {
        //     $array['value'] = str_replace('=', '', $value);
        //     $array['function1'] = 'unknown-function1';
        //     $array['function2'] = 'unknown-function2';
        // }
        return $array;
    }

    public function edit(){
        $policies = Policy::where('user_profile_id', Auth::user()->userProfile()->first()['id'])->get();
        //$users = User::all();
        $users = User::where('id', Auth::user()->id)->get();
        $devices = UserProfile::where('user_id', Auth::user()['id'])->first()->devices()->get();
        $policyAttributes = PolicyAttribute::all();
        return view('policy.edit', compact('policies', 'users', 'devices', 'policyAttributes'));
    }

    public function remove(){
        $policies = Policy::where('user_profile_id', Auth::user()->userProfile()->first()['id'])->get();
        return view('policy.remove', compact('policies'));
    }
    public function delete(Request $request){
        $policy = Policy::where('id', $request['policy'])->first();
        if($policy){
            if (file_exists(resource_path('policy/'.$policy['id'].'.xml'))){
                if(unlink(resource_path('policy/'.$policy['id'].'.xml'))){
                Session::flash('flash_message', 'Policy successfully removed!');
                Session::flash('flash_type', 'alert-success');
                $policy->delete();
            } else {
                Session::flash('flash_message', 'Policy not remove!');
                Session::flash('flash_type', 'alert-success');
            }
            }else {
                $policy->delete();
            }
            
        } else {
            Session::flash('flash_message', 'Policy not remove!');
            Session::flash('flash_type', 'alert-success');
        }
        return redirect()->back();
    }

    public function update(Request $request){
        // dd($request);
        $userId = UserProfile::where('id', $request['subject'])->first()->user()->first()->id;
        $tmp = [
            'subject' => User::where('id', $userId)->first()['username'],
            'action' => $request['action'],
            'resource' => Device::where('id', $request['resource'])->first()['device_eui'],
            'operation' => $request['condition_type'],
            'policy-description' => $request['policy_description']
        ];
        
        $conditions = [];
        for($i =0; $i< count($request['eui_name']); $i++){
            //dd($request['eui_name'][$i]);
            if ($request['eui_value'][$i] == null || $request['eui_value'][$i] == ''){
                continue;
            }
            $subVal = substr($request['eui_value'][$i], 1, strlen($request['eui_value'][$i])-1);
            if (is_numeric($subVal)){
                $tmp2 = 'double';
                // dd($tmp);
            } else {
                $tmp2 = 'string';
            }

            $array = [
                'name' => Device::where('id', $request['eui_name'][$i])->first()['device_eui'],
                'value' => $request['eui_value'][$i],
                'type' => 'Sensor',
                'data_type' => $tmp2
            ];
            $array = $this->pushFunctionTypeOfCondition($array);
            array_push($conditions, $array);
        }
        for($i =0; $i< count($request['env_type']); $i++){
            if ($request['env_value'][$i] == null || $request['env_value'][$i] == ''){
                continue;
            }
            $array = [
                'name' => $request['env_type'][$i],
                'value' => $request['env_value'][$i],
                'type' => $request['env_type'][$i],
                'data_type' => strtolower($request['env_type'][$i])
             ];
            $array = $this->pushFunctionTypeOfCondition($array);
            array_push($conditions, $array);
        }

        $tmp['condition'] = $conditions;
 
        $userProfile = UserProfile::where('user_id', Auth::user()['id'])->first();

        // if policy already exist
        $new = Policy::where([['device_id', $request['resource']], ['subject_id', $request['subject']], ['action', $request['action']]])->first();
        if($new != null){
            $new->update([
                'user_profile_id' => $userProfile['id'],
                'request' => json_encode($tmp),
                'type' => $request['policy_type'],
                'device_id' => $request['resource'],
                'condition_type' => $request['condition_type'],
                'description' => $request['policy_description'],
                'subject_id' => $request['subject'],
                'action' => $request['action']
            ]);
        } else {
            $new = Policy::create([
                'user_profile_id' => $userProfile['id'],
                'request' => json_encode($tmp),
                'type' => $request['policy_type'],
                'device_id' => $request['resource'],
                'condition_type' => $request['condition_type'],
                'description' => $request['policy_description'],
                'subject_id' => $request['subject'],
                'action' => $request['action']
            ]);
        }

        if(!$this->createPolicyFile($new['id'], $tmp, $conditions)){
            Session::flash('flash_message', 'Fail to create policy file');
            Session::flash('flash_type', 'alert-danger');
            // todo: delete policy from database
            return redirect()->back();
        }

        Session::flash('flash_message', 'Policy successfully updated!');
        Session::flash('flash_type', 'alert-success');

        return redirect()->back();
    }
    public function getXmlFile(Request $request){
        $id = $request['id'];
        $xml = file_get_contents(resource_path('policy/'.$id.'.xml'));
        return [
            'data' => $xml
        ];
    }
}
