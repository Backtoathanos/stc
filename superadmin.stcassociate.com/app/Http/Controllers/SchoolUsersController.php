<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserSchool;
use App\Http\Controllers\Concerns\RespondsForModalRequests;

class SchoolUsersController extends Controller
{
    use RespondsForModalRequests;

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:stc_school,stc_school_user_email',
            'contact' => 'required|numeric|min:10|unique:stc_school,stc_school_user_contact',
        ]);
        $user = new UserSchool;
        $user->stc_school_user_fullName = $request->name;
        $user->stc_school_user_email = $request->email;
        $user->stc_school_user_contact = $request->contact;
        $user->stc_school_user_address = $request->address;
        $user->stc_school_user_cityid = $request->city;
        $user->stc_school_user_stateid = $request->state;
        $user->stc_school_user_pincode = $request->pincode;
        $user->stc_school_user_aboutyou = $request->abtuser;
        $user->stc_school_user_password = $request->password;
        $user->stc_school_user_for = $request->role;
        $user->stc_school_user_status = $request->status;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'School user created successfully!');
    }

    public function update($id, Request $request)
    {
        $user = UserSchool::getSingle($id);
        $user->stc_school_user_fullName = $request->name;
        $user->stc_school_user_email = $request->email;
        $user->stc_school_user_contact = $request->contact;
        $user->stc_school_user_address = $request->address;
        $user->stc_school_user_cityid = $request->city;
        $user->stc_school_user_stateid = $request->state;
        $user->stc_school_user_pincode = $request->pincode;
        $user->stc_school_user_aboutyou = $request->abtuser;
        if (! empty($request->password)) {
            $user->stc_school_user_password = $request->password;
        }
        $user->stc_school_user_for = $request->role;
        $user->stc_school_user_status = $request->status;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'School user updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $user = UserSchool::getSingle($id);
        $user->delete();

        return $this->modalSuccess($request, url('/users'), 'School user deleted successfully!');
    }
}
