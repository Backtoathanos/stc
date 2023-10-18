<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserSite;
use App\City;
use App\State;
use App\UserManager;

class SiteUsersController extends Controller
{
    public function show(){
        $data['page_title']="Site Users";
        $data['getRecord'] = UserSite::getAdmin();
        return view('pages.usersite', $data);
    }

    public function create(){
        $data['page_title']="Site Users";
        $data['getRecord'] = UserSite::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        $data['getRecordManger'] = UserManager::getManagerForSite();
        return view('pages.usersiteadd', $data);
    }

    public function edit($id){
        $data['page_title']="Site Users";
        $data['getRecord']=UserSite::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        $data['getRecordManger'] = UserManager::getManagerForSite();
        return view('pages.usersiteedit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_cust_pro_supervisor,stc_cust_pro_supervisor_email',
            'contact' => 'required|numeric|min:10|unique:stc_cust_pro_supervisor,stc_cust_pro_supervisor_contact'
        ]);
            $user = new UserSite;
            $user->stc_cust_pro_supervisor_cust_id = $request->customer;
            $user->stc_cust_pro_supervisor_fullname = $request->name;
            $user->stc_cust_pro_supervisor_email = $request->email;
            $user->stc_cust_pro_supervisor_contact = $request->contact;
            $user->stc_cust_pro_supervisor_whatsapp = $request->whatsapp;
            $user->stc_cust_pro_supervisor_address = $request->address;
            $user->stc_cust_pro_supervisor_cityid = $request->city;
            $user->stc_cust_pro_supervisor_state_id = $request->state;
            $user->stc_cust_pro_supervisor_pincode = $request->pincode;
            $user->stc_cust_pro_supervisor_password = $request->password;
            $user->stc_cust_pro_supervisor_category = $request->category;
            $user->stc_cust_pro_supervisor_status = $request->status;
            $user->stc_cust_pro_supervisor_created_by = $request->manager;
            $user->save();

        return redirect('/users/siteusers')->with('success', 'Site user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserSite::getSingle($id);
        $user->stc_cust_pro_supervisor_cust_id = $request->customer;
        $user->stc_cust_pro_supervisor_fullname = $request->name;
        $user->stc_cust_pro_supervisor_email = $request->email;
        $user->stc_cust_pro_supervisor_contact = $request->contact;
        $user->stc_cust_pro_supervisor_whatsapp = $request->whatsapp;
        $user->stc_cust_pro_supervisor_address = $request->address;
        $user->stc_cust_pro_supervisor_cityid = $request->city;
        $user->stc_cust_pro_supervisor_state_id = $request->state;
        $user->stc_cust_pro_supervisor_pincode = $request->pincode;
        if(!empty($request->password)){
            $user->stc_cust_pro_supervisor_password = $request->password;
        }
        $user->stc_cust_pro_supervisor_category = $request->category;
        $user->stc_cust_pro_supervisor_status = $request->status;
        $user->stc_cust_pro_supervisor_created_by = $request->manager;
        $user->save();

        return redirect('/users/siteusers')->with('success', 'Site user updated successfully!');
    }

    public function delete($id){
        $user = UserSite::getSingle($id);
        $user->delete();

        return redirect('/users/siteusers')->with('success', 'Site user deleted successfully!');
    }
}
