<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\User;

class AdminUsersController extends Controller
{
    public function show(){
        $data['page_title']="Admin Show";
        $data['getRecord'] = User::getAdmin();
        return view('pages.useradmin', $data);
    }

    public function create(){
        $data['page_title']="Admin Create";
        $data['getRecord'] = User::getAdmin();
        return view('pages.useradminadd', $data);
    }

    public function edit($id){
        $data['getRecord']=User::getSingle($id);
        $data['page_title']="Admin Edit";
        return view('pages.useradminedit', $data);
    }

    public function insert(Request $request){
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = $request->status;
        $user->save();

        return redirect('/users/admin')->with('success', 'Admin created successfully!');
    }

    public function update($id, Request $request){
        $user = User::getSingle($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
        }    
        $user->status = $request->status;
        $user->save();

        return redirect('/users/admin')->with('success', 'Admin updated successfully!');
    }

    public function delete($id){
        $user = User::getSingle($id);
        $user->delete();

        return redirect('/users/admin')->with('success', 'Admin deleted successfully!');

        // $user = User::find($id); 
        // $user->delete(); //delete the client

        // // get list of all projects of client
        // $projects = DB::table('client_project')->where('client_id',$id)->get();

        // DB::table('client_project')->where('client_id',$id)->delete(); 

        // return redirect()->route('admin.clients');
    }
}
