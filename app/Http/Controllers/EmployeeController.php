<?php

namespace App\Http\Controllers;

use App\Models\touchStarEmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    //
    public function index(){
        $employee_details = touchStarEmp::where('emp_id', Auth::guard('touchstaraccount')->user()->emp_id)->first();
        $employees = touchStarEmp::all();
        return view('auth.register',compact('employee_details', 'employees'));
    }

    public function addData(Request $req){
        touchStarEmp::create([
            'emp_first_name' => $req->input('firstName'),
            'emp_last_name' => $req->input('lastName'),
            'emp_phone' => $req->input('phone'),
            'emp_viber' => $req->input('viber'),
            'emp_socmed' => $req->input('social'),
            'emp_deparment' => $req->input('dept'),
            'emp_position' => $req->input('position'),
            'emp_role' => "EMPLOYEE",
            'emp_signature' => $req->input('signature'),
            'emp_status' => $req->input('status'),
            'emp_profile'=>$req->input('profilePic')   
        ])->save();
        return response()->json(['message' => 'Employee added successfully']);
    }
}
