<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Requisition;

class RequisitionController extends Controller
{
    public function show(){
        $data['page_title']="Requisitions";
        // $data['getRecord'] = BranchSchool::getSchool();
        return view('pages.Requisition', $data);
    }
}
