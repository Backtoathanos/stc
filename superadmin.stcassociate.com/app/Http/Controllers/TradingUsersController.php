<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\UserTrading;

class TradingUsersController extends Controller
{
    public function show(){
        $data['page_title']="Trading Users";
        return view('pages.tradinguser', $data);
    }

    public function create(){
        $data['page_title']="Trading Users";
        return view('pages.tradinguseradd', $data);
    }

    public function edit(){
        $data['page_title']="Dashboard";
        return view('pages.dashboard', $data);
    }

    public function delete(){
        $data['page_title']="Dashboard";
        return view('pages.dashboard', $data);
    }

    public function insert(Request $request){
        $user = new User;
        $user->name = $request->name;
    }
}
