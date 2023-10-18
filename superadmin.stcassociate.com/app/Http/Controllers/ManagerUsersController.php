<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserManager;
use App\City;
use App\State;

class ManagerUsersController extends Controller
{
    public function show(){
        $data['page_title']="Manager Users";
        $data['getRecord'] = UserManager::getAdmin();
        return view('pages.usermanager', $data);
    }

    public function create(){
        $data['page_title']="Manager Users";
        $data['getRecord'] = UserManager::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.usermanageradd', $data);
    }

    public function edit($id){
        $data['page_title']="Manager Users";
        $data['getRecord']=UserManager::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.usermanageredit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_agents,stc_agents_email',
            'contact' => 'required|numeric|min:10|unique:stc_agents,stc_agents_contact'
        ]);
            $user = new UserManager;
            $user->stc_agents_name = $request->name;
            $user->stc_agents_email = $request->email;
            $user->stc_agents_contact = $request->contact;
            $user->stc_agents_address = $request->address;
            $user->stc_agents_city_id = $request->city;
            $user->stc_agents_state_id = $request->state;
            $user->stc_agents_pincode = $request->pincode;
            $user->stc_agents_userid = $request->userid;
            $user->stc_agents_pass = $request->password;
            $user->stc_agents_role = $request->role;
            $user->stc_agents_status = $request->status;
            $user->save();

        return redirect('/users/managerusers')->with('success', 'Manager user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserManager::getSingle($id);
        $user->stc_agents_name = $request->name;
        $user->stc_agents_email = $request->email;
        $user->stc_agents_contact = $request->contact;
        $user->stc_agents_address = $request->address;
        $user->stc_agents_city_id = $request->city;
        $user->stc_agents_state_id = $request->state;
        $user->stc_agents_pincode = $request->pincode;
        $user->stc_agents_userid = $request->userid;
        if(!empty($request->password)){
            $user->stc_agents_pass = $request->password;
        }
        $user->stc_agents_role = $request->role;
        $user->stc_agents_status = $request->status;
        $user->save();

        return redirect('/users/managerusers')->with('success', 'Manager user updated successfully!');
    }

    public function delete($id){
        $user = UserManager::getSingle($id);
        $user->delete();

        return redirect('/users/managerusers')->with('success', 'Manager user deleted successfully!');
    }
}
