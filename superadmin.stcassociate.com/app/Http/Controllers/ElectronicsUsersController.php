<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserElectronics;
use App\City;
use App\State;

class ElectronicsUsersController extends Controller
{
    public function show(){
        $data['page_title']="Electronics Users";
        $data['getRecord'] = UserElectronics::getAdmin();
        return view('pages.userelectronics', $data);
    }

    public function create(){
        $data['page_title']="Electronics Users";
        $data['getRecord'] = UserElectronics::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.userelectronicsadd', $data);
    }

    public function edit($id){
        $data['page_title']="Electronics Users";
        $data['getRecord']=UserElectronics::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.userelectronicsedit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_electronics_user,stc_electronics_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_electronics_user,stc_electronics_user_contact'
        ]);
            $user = new UserElectronics;
            $user->stc_electronics_user_fullName = $request->name;
            $user->stc_electronics_user_email = $request->email;
            $user->stc_electronics_user_contact = $request->contact;
            $user->stc_electronics_user_address = $request->address;
            $user->stc_electronics_user_cityid = $request->city;
            $user->stc_electronics_user_stateid = $request->state;
            $user->stc_electronics_user_pincode = $request->pincode;
            $user->stc_electronics_user_aboutyou = $request->abtuser;
            $user->stc_electronics_user_password = $request->password;
            $user->stc_electronics_user_for = $request->role;
            $user->stc_electronics_user_status = $request->status;
            $user->save();

        return redirect('/users/electronicsusers')->with('success', 'Electronics user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserElectronics::getSingle($id);
        $user->stc_electronics_user_fullName = $request->name;
        $user->stc_electronics_user_email = $request->email;
        $user->stc_electronics_user_contact = $request->contact;
        $user->stc_electronics_user_address = $request->address;
        $user->stc_electronics_user_cityid = $request->city;
        $user->stc_electronics_user_stateid = $request->state;
        $user->stc_electronics_user_pincode = $request->pincode;
        $user->stc_electronics_user_aboutyou = $request->abtuser;
        if(!empty($request->password)){
            $user->stc_electronics_user_password = $request->password;
        }
        $user->stc_electronics_user_for = $request->role;
        $user->stc_electronics_user_status = $request->status;
        $user->save();

        return redirect('/users/electronicsusers')->with('success', 'Electronics user updated successfully!');
    }

    public function delete($id){
        $user = UserElectronics::getSingle($id);
        $user->delete();

        return redirect('/users/electronicsusers')->with('success', 'Electronics user deleted successfully!');
    }
}
