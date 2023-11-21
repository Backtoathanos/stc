<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserSchool;
use App\City;
use App\State;

class SchoolUsersController extends Controller
{
    public function show(){
        $data['page_title']="School Users";
        $data['getRecord'] = UserSchool::getAdmin();
        return view('pages.userschool', $data);
    }

    public function create(){
        $data['page_title']="School Users";
        $data['getRecord'] = UserSchool::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.userschooladd', $data);
    }

    public function edit($id){
        $data['page_title']="School Users";
        $data['getRecord']=UserSchool::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.userschooledit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_electronics_user,stc_electronics_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_electronics_user,stc_electronics_user_contact'
        ]);
            $user = new UserSchool;
            $user->stc_school_user_fullName = $request->name;
            $user->stc_school_user_email = $request->email;
            $user->stc_school_user_contact = $request->contact;
            $user->stc_school_user_address = $request->address;
            $user->stc_school_user_cityid = $request->city;
            $user->stc_school_user_stateid = $request->state;
            $user->stc_school_user_pincode = $request->pincode;
            $user->stc_school_user_aboutyou = $request->abtuser;
            $user->stc_school_user_password = $request->password;
            $user->stc_school_user_for = $request->role;
            $user->stc_school_user_status = $request->status;
            $user->save();

        return redirect('/users/schoolusers')->with('success', 'School user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserSchool::getSingle($id);
        $user->stc_school_user_fullName = $request->name;
        $user->stc_school_user_email = $request->email;
        $user->stc_school_user_contact = $request->contact;
        $user->stc_school_user_address = $request->address;
        $user->stc_school_user_cityid = $request->city;
        $user->stc_school_user_stateid = $request->state;
        $user->stc_school_user_pincode = $request->pincode;
        $user->stc_school_user_aboutyou = $request->abtuser;
        if(!empty($request->password)){
            $user->stc_school_user_password = $request->password;
        }
        $user->stc_school_user_for = $request->role;
        $user->stc_school_user_status = $request->status;
        $user->save();

        return redirect('/users/schoolusers')->with('success', 'School user updated successfully!');
    }

    public function delete($id){
        $user = UserSchool::getSingle($id);
        $user->delete();

        return redirect('/users/schoolusers')->with('success', 'School user deleted successfully!');
    }
}
