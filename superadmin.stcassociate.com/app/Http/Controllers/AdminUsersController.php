<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserAdmin;
use App\City;
use App\State;

class AdminUsersController extends Controller
{
    public function show(){
        $data['page_title']="Admin Users";
        $data['getRecord'] = UserAdmin::getAdmin();
        return view('pages.useruadmin', $data);
    }

    public function create(){
        $data['page_title']="Admin Users";
        $data['getRecord'] = UserAdmin::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.useruadminadd', $data);
    }

    public function edit($id){
        $data['page_title']="Admin Users";
        $data['getRecord']=UserAdmin::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.useruadminedit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_user,stc_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_user,stc_user_phone'
        ]);
            $user = new UserAdmin;
            $user->stc_user_name = $request->name;
            $user->stc_user_email = $request->email;
            $user->stc_user_phone = $request->contact;
            $user->stc_user_phone_again = $request->contact2;
            $user->stc_user_address = $request->address;
            $user->stc_user_city_id = $request->city;
            $user->stc_user_state_id = $request->state;
            $user->stc_user_img = '';
            $user->stc_user_userid = $request->userid;
            $user->stc_user_password = $request->password;
            $user->stc_user_role = $request->role;
            $user->stc_user_status = $request->status;
            $user->save();

        return redirect('/users/uadminusers')->with('success', 'Admin user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserAdmin::getSingle($id);
        $user->stc_user_name = $request->name;
        $user->stc_user_email = $request->email;
        $user->stc_user_phone = $request->contact;
        $user->stc_user_phone_again = $request->contact2;
        $user->stc_user_address = $request->address;
        $user->stc_user_city_id = $request->city;
        $user->stc_user_state_id = $request->state;
        $user->stc_user_userid = $request->userid;
        if(!empty($request->password)){
            $user->stc_user_password = $request->password;
        }
        $user->stc_user_role = $request->role;
        $user->stc_user_status = $request->status;
        $user->save();

        return redirect('/users/uadminusers')->with('success', 'Admin user updated successfully!');
    }

    public function delete($id){
        $user = UserAdmin::getSingle($id);
        $user->delete();

        return redirect('/users/uadminusers')->with('success', 'Admin user deleted successfully!');
    }
}
