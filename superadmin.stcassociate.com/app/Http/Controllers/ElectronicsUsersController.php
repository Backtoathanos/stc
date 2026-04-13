<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserElectronics;
use App\Http\Controllers\Concerns\RespondsForModalRequests;

class ElectronicsUsersController extends Controller
{
    use RespondsForModalRequests;

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:stc_electronics_user,stc_electronics_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_electronics_user,stc_electronics_user_contact',
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

        return $this->modalSuccess($request, url('/users'), 'Electronics user created successfully!');
    }

    public function update($id, Request $request)
    {
        $user = UserElectronics::getSingle($id);
        $user->stc_electronics_user_fullName = $request->name;
        $user->stc_electronics_user_email = $request->email;
        $user->stc_electronics_user_contact = $request->contact;
        $user->stc_electronics_user_address = $request->address;
        $user->stc_electronics_user_cityid = $request->city;
        $user->stc_electronics_user_stateid = $request->state;
        $user->stc_electronics_user_pincode = $request->pincode;
        $user->stc_electronics_user_aboutyou = $request->abtuser;
        if (! empty($request->password)) {
            $user->stc_electronics_user_password = $request->password;
        }
        $user->stc_electronics_user_for = $request->role;
        $user->stc_electronics_user_status = $request->status;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'Electronics user updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $user = UserElectronics::getSingle($id);
        $user->delete();

        return $this->modalSuccess($request, url('/users'), 'Electronics user deleted successfully!');
    }
}
