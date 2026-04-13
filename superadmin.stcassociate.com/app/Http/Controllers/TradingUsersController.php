<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserTrading;
use App\Http\Controllers\Concerns\RespondsForModalRequests;

class TradingUsersController extends Controller
{
    use RespondsForModalRequests;

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:stc_trading_user,stc_trading_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_trading_user,stc_trading_user_cont',
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

        return $this->modalSuccess($request, url('/users'), 'Trading user created successfully!');
    }

    public function update($id, Request $request)
    {
        $user = UserTrading::getSingle($id);
        $user->stc_trading_user_name = $request->name;
        $user->stc_trading_user_email = $request->email;
        $user->stc_trading_user_cont = $request->contact;
        $user->stc_trading_user_address = $request->address;
        $user->stc_trading_user_city_id = $request->city;
        $user->stc_trading_user_state_id = $request->state;
        $user->stc_trading_user_pincode = $request->pincode;
        $user->stc_trading_user_location = $request->branch;
        if (! empty($request->password)) {
            $user->stc_trading_user_password = $request->password;
        }
        $user->stc_trading_user_status = $request->status;
        $user->stc_trading_user_created_by = 1;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'Trading user updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $user = UserTrading::getSingle($id);
        $user->delete();

        return $this->modalSuccess($request, url('/users'), 'Trading user deleted successfully!');
    }
}
