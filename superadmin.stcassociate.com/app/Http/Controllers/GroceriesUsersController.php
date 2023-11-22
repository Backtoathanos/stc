<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserGroceries;
use App\City;
use App\State;

class GroceriesUsersController extends Controller
{
    public function show(){
        $data['page_title']="Groceries Users";
        $data['getRecord'] = UserGroceries::getAdmin();
        return view('pages.usergroceries', $data);
    }

    public function create(){
        $data['page_title']="Groceries Users";
        $data['getRecord'] = UserGroceries::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.usergroceriesadd', $data);
    }

    public function edit($id){
        $data['page_title']="Groceries Users";
        $data['getRecord']=UserGroceries::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.usergroceriesedit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_groceries_user,stc_groceries_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_groceries_user,stc_groceries_user_cont'
        ]);
            $user = new UserGroceries;
            $user->stc_groceries_user_name = $request->name;
            $user->stc_groceries_user_email = $request->email;
            $user->stc_groceries_user_cont = $request->contact;
            $user->stc_groceries_user_address = $request->address;
            $user->stc_groceries_user_city_id = $request->city;
            $user->stc_groceries_user_state_id = $request->state;
            $user->stc_groceries_user_pincode = $request->pincode;
            $user->stc_groceries_user_password = $request->password;
            $user->stc_groceries_user_is_user = $request->usertype;
            $user->stc_groceries_user_status = $request->status;
            $user->stc_groceries_user_created_by = 1;
            $user->save();

        return redirect('/users/groceriesusers')->with('success', 'Groceries user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserGroceries::getSingle($id);
        $user->stc_groceries_user_name = $request->name;
        $user->stc_groceries_user_email = $request->email;
        $user->stc_groceries_user_cont = $request->contact;
        $user->stc_groceries_user_address = $request->address;
        $user->stc_groceries_user_city_id = $request->city;
        $user->stc_groceries_user_state_id = $request->state;
        $user->stc_groceries_user_pincode = $request->pincode;
        if(!empty($request->password)){
            $user->stc_groceries_user_password = $request->password;
        }
        $user->stc_groceries_user_is_user = $request->usertype;
        $user->stc_groceries_user_status = $request->status;
        $user->stc_groceries_user_created_by = 1;
        $user->save();

        return redirect('/users/groceriesusers')->with('success', 'Groceries user updated successfully!');
    }

    public function delete($id){
        $user = UserGroceries::getSingle($id);
        $user->delete();

        return redirect('/users/groceriesusers')->with('success', 'Groceries user deleted successfully!');
    }
}
