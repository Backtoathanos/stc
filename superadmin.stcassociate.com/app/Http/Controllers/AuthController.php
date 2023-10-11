<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;

class AuthController extends Controller
{
    public function login_admin(){
        // dd(Hash::make(123456));
        if(!empty(Auth::check()) && Auth::user()->status==1){
            return redirect('dashboard');
        }else{
            return view('pages.welcome');
        }
    }

    public function auth_login_admin(Request $request){
        // dd($request->all());
        $remember = !empty($request->remember) ? true : false;
        if(Auth::attempt(['email'=>$request->userid, 'password'=>$request->password], $remember)){
            return redirect('dashboard');
        }else{
            return redirect()->back()->with('error', "Invalid Credential!");
        }
    }

    public function logout_admin(){
        Auth::logout();
        return redirect('/');
    }
}
