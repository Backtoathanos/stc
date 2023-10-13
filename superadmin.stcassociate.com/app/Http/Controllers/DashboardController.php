<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(){
        $data['page_title']="Dashboard";
        return view('pages.dashboard', $data);
    }
}
