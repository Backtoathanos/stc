<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserAdmin;
use App\Http\Controllers\Concerns\RespondsForModalRequests;

class AdminUsersController extends Controller
{
    use RespondsForModalRequests;

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:stc_user,stc_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_user,stc_user_phone',
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

        return $this->modalSuccess($request, url('/users'), 'Admin user created successfully!');
    }

    public function update($id, Request $request)
    {
        $user = UserAdmin::getSingle($id);
        $user->stc_user_name = $request->name;
        $user->stc_user_email = $request->email;
        $user->stc_user_phone = $request->contact;
        $user->stc_user_phone_again = $request->contact2;
        $user->stc_user_address = $request->address;
        $user->stc_user_city_id = $request->city;
        $user->stc_user_state_id = $request->state;
        $user->stc_user_userid = $request->userid;
        if (! empty($request->password)) {
            $user->stc_user_password = $request->password;
        }
        $user->stc_user_role = $request->role;
        $user->stc_user_status = $request->status;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'Admin user updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $user = UserAdmin::getSingle($id);
        $user->delete();

        return $this->modalSuccess($request, url('/users'), 'Admin user deleted successfully!');
    }
}
