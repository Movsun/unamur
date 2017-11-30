<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Setting;
use Hash;
use Illuminate\Support\Facades\Session;
use App\User;

class SettingController extends Controller
{
    //

    public function index(){

    	return view('setting.index');
    }
    public function add(){

    	return view('setting.add');
    }
    public function edit(){

    	return view('setting.edit');
    }
    public function remove(){
        $settings = Setting::where('user_profile_id', Auth::user()->userProfile()->first()->id)->get();
    	return view('setting.remove', compact('settings'));
    }
    public function show(){
        $settings = Setting::where('user_profile_id', Auth::user()->userProfile()->first()->id)->get();
    	return view('setting.show', compact('settings'));
    }
    public function store(Request $request){

        return redirect()->back();
    }

    public function update(Request $request){
        return redirect()->back();
    }
    public function delete(Request $request)
    {
        return redirect()->back();
    }

    public function changePassword(){
        // $user = Auth::user();
        return view('setting.change-password', compact('user'));
    }
    public function changeEmail(){
        // $user = Auth::user();
        return view('setting.change-email', compact('user'));
    }

    public function updatePassword(Request $request){
        $user = Auth::user();
        if (! Hash::check( $request['old_password'], $user->password)){
            Session::flash('flash_message', 'Error! Wrong Password!');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();
        }
        if ($request['new_password']  != $request['confirm_password']){
            Session::flash('flash_message', 'Error! Confirm password does not match!');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();    
        }
        if (strlen($request['new_password']) < 6){
            Session::flash('flash_message', 'Error! Password must be 6 characters or more!');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();    
        }

        $user->update(['password' => bcrypt($request['new_password'])]);
        Session::flash('flash_message', 'Password successfully changed!');
        Session::flash('flash_type', 'alert-success');
        return redirect()->back();
    }

    public function updateEmail(Request $request){

        $user = Auth::user();
        if (! Hash::check( $request['password'], $user->password)){
            Session::flash('flash_message', 'Error! Wrong Password!');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();
        }
        if (User::where('email', $request['email'])->first() != null){
            Session::flash('flash_message', 'Error! Email has already been taken!');
            Session::flash('flash_type', 'alert-danger');
            return redirect()->back();    
        }

        $user->update(['email' => $request['email']]);
        Session::flash('flash_message', 'Email successfully changed!');
        Session::flash('flash_type', 'alert-success');
        return redirect()->back();
    }
   

}
