<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserTrading;
use App\City;
use App\State;

class TradingUsersController extends Controller
{
    public function show(){
        $data['page_title']="Trading Users";
        $data['getRecord'] = UserTrading::getAdmin();
        return view('pages.usertrading', $data);
    }

    public function create(){
        $data['page_title']="Trading Users";
        $data['getRecord'] = UserTrading::getAdmin();
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.usertradingadd', $data);
    }

    public function edit($id){
        $data['page_title']="Trading Users";
        $data['getRecord']=UserTrading::getSingle($id);
        $data['getRecordCity'] = City::getCity();
        $data['getRecordState'] = State::getState();
        return view('pages.usertradingedit', $data);
    }

    public function insert(Request $request){
        request()->validate([
            'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_trading_user,stc_trading_user_cont'
        ]);
            $user = new UserTrading;
            $user->stc_trading_user_name = $request->name;
            $user->stc_trading_user_email = $request->email;
            $user->stc_trading_user_cont = $request->contact;
            $user->stc_trading_user_address = $request->address;
            $user->stc_trading_user_city_id = $request->city;
            $user->stc_trading_user_state_id = $request->state;
            $user->stc_trading_user_pincode = $request->pincode;
            $user->stc_trading_user_password = $request->password;
            $user->stc_trading_user_status = $request->status;
            $user->stc_trading_user_location = $request->branch;
            $user->stc_trading_user_created_by = 1;
            $user->save();

        return redirect('/users/tradingusers')->with('success', 'Trading user created successfully!');
    }

    public function update($id, Request $request){
        // request()->validate([
        //     'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email,'.$id,
        //     'contact' => 'required|numeric|unique:stc_trading_user,stc_trading_user_cont,'.$id
        // ]);
        $user = UserTrading::getSingle($id);
        $user->stc_trading_user_name = $request->name;
        $user->stc_trading_user_email = $request->email;
        $user->stc_trading_user_cont = $request->contact;
        $user->stc_trading_user_address = $request->address;
        $user->stc_trading_user_city_id = $request->city;
        $user->stc_trading_user_state_id = $request->state;
        $user->stc_trading_user_pincode = $request->pincode;
        $user->stc_trading_user_location = $request->branch;
        if(!empty($request->password)){
            $user->stc_trading_user_password = $request->password;
        }    
        $user->stc_trading_user_status = $request->status;
        $user->stc_trading_user_created_by = 1;
        $user->save();

        return redirect('/users/tradingusers')->with('success', 'Trading user updated successfully!');
    }

    public function delete($id){
        $user = UserTrading::getSingle($id);
        $user->delete();

        return redirect('/users/tradingusers')->with('success', 'Trading user deleted successfully!');
    }
}
