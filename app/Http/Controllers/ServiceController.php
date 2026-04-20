<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\touchStarEmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function report(){
        $employee_details = touchStarEmp::where('emp_id', Auth::guard('touchstaraccount')->user()->emp_id)->first();
        $machines = Machine::all();
        return view('service.report',compact('employee_details','machines'));
    }
}
