<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserGroceries;
use App\Http\Controllers\Concerns\RespondsForModalRequests;

class GroceriesUsersController extends Controller
{
    use RespondsForModalRequests;

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:stc_groceries_user,stc_groceries_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_groceries_user,stc_groceries_user_cont',
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

        return $this->modalSuccess($request, url('/users'), 'Groceries user created successfully!');
    }

    public function update($id, Request $request)
    {
        $user = UserGroceries::getSingle($id);
        $user->stc_groceries_user_name = $request->name;
        $user->stc_groceries_user_email = $request->email;
        $user->stc_groceries_user_cont = $request->contact;
        $user->stc_groceries_user_address = $request->address;
        $user->stc_groceries_user_city_id = $request->city;
        $user->stc_groceries_user_state_id = $request->state;
        $user->stc_groceries_user_pincode = $request->pincode;
        if (! empty($request->password)) {
            $user->stc_groceries_user_password = $request->password;
        }
        $user->stc_groceries_user_is_user = $request->usertype;
        $user->stc_groceries_user_status = $request->status;
        $user->stc_groceries_user_created_by = 1;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'Groceries user updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $user = UserGroceries::getSingle($id);
        $user->delete();

        return $this->modalSuccess($request, url('/users'), 'Groceries user deleted successfully!');
    }
}
