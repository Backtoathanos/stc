<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserManager;
use App\Http\Controllers\Concerns\RespondsForModalRequests;

class ManagerUsersController extends Controller
{
    use RespondsForModalRequests;

    public function insert(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:stc_agents,stc_agents_email',
            'contact' => 'required|numeric|min:10|unique:stc_agents,stc_agents_contact',
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

        return $this->modalSuccess($request, url('/users'), 'Manager user created successfully!');
    }

    public function update($id, Request $request)
    {
        $user = UserManager::getSingle($id);
        $user->stc_agents_name = $request->name;
        $user->stc_agents_email = $request->email;
        $user->stc_agents_contact = $request->contact;
        $user->stc_agents_address = $request->address;
        $user->stc_agents_city_id = $request->city;
        $user->stc_agents_state_id = $request->state;
        $user->stc_agents_pincode = $request->pincode;
        $user->stc_agents_userid = $request->userid;
        if (! empty($request->password)) {
            $user->stc_agents_pass = $request->password;
        }
        $user->stc_agents_role = $request->role;
        $user->stc_agents_status = $request->status;
        $user->save();

        return $this->modalSuccess($request, url('/users'), 'Manager user updated successfully!');
    }

    public function delete(Request $request, $id)
    {
        $user = UserManager::getSingle($id);
        $user->delete();

        return $this->modalSuccess($request, url('/users'), 'Manager user deleted successfully!');
    }
}
