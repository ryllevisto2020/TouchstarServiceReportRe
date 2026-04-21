<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\ServiceReport;
use App\Models\touchStarEmp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function report(){
        $employee_details = touchStarEmp::where('emp_id', Auth::guard('touchstaraccount')->user()->emp_id)->first();
        $machines = Machine::all();
        return view('service.report',compact('employee_details','machines'));
    }
    public function addReport(Request $req){
        $machine_id = $req->input('machine_id');
        $service_type = $req->input('service_type');
        $identification = $req->input('identification');
        $root_cause = $req->input('root_cause');
        $action_taken = $req->input('action_taken');
        $equipment_status = $req->input('equipment_status');
        $recommendations = $req->input('recommendations');
        $qty = $req->input('qty');
        $particulars = $req->input('particulars');
        $si_dr_no = $req->input('si_dr_no');
        $medtech_signature = $req->input('medtech_signature');
        $approved_by = $req->input('approved_by');
        $service_engineer = $req->input('service_engineer');
        $service_engineer_department = $req->input('service_engineer_department');
        $before_images = $req->file('before_images');
        $after_images = $req->file('after_images');
        $images = $req->file('images');
        $calibration_images = $req->file('calibration_images');

        $part_replaced = [];
        for ($i=0; $i < count($qty); $i++) { 
            # code...
            $part = [
                'qty'=>$qty[$i],
                'particulars'=>$particulars[$i],
                'si_dr_no'=>$si_dr_no[$i]
            ];
            array_push($part_replaced, $part);
        }

        $service_images_paths = [];
        if($images != null){
            for ($i=0; $i < count($images); $i++) { 
                $path = $images[$i]->store('service_images', 'private');
                array_push($service_images_paths, $path);
            }
        }

        $before_images_paths = [];
        if($before_images != null){
            for ($i=0; $i < count($before_images); $i++) {
                $path = $before_images[$i]->store('before_images', 'private');
                array_push($before_images_paths, $path);
            }
        }

        $after_images_paths = [];
        if($after_images != null){
            for ($i=0; $i < count($after_images); $i++) {
                $path = $after_images[$i]->store('after_images', 'private');
                array_push($after_images_paths, $path);
            }
        }

        $calibration_images_paths = [];
        if($calibration_images != null){
            for ($i=0; $i < count($calibration_images); $i++) {
                $path = $calibration_images[$i]->store('calibration_images', 'private');
                array_push($calibration_images_paths, $path);
            }
        }
        
        $emp_id = touchStarEmp::where('emp_id',Auth::guard('touchstaraccount')->user()->emp_id)->first();

        ServiceReport::create([
            'machine_id'=>$machine_id ,
            'service_type'=>json_encode(collect($service_type)->reject(fn($item)=> $item == null)->all()),
            'identification_verification'=>$identification,
            'root_cause_findings'=>$root_cause,
            'action_taken'=>$action_taken,
            'equipment_status'=>$equipment_status,
            'recommendations'=>$recommendations,
            'parts_replaced'=>json_encode($part_replaced),
            'approved_by'=>$approved_by,
            'medtech_signature'=>$medtech_signature,
            'service_engineer'=>$service_engineer,
            'service_engineer_department'=>$service_engineer_department,
            'service_date'=>now()->format('Y-m-d'),
            'service_images'=>json_encode($service_images_paths),
            'before_images'=>json_encode($before_images_paths),
            'after_images'=>json_encode($after_images_paths),
            'calibration_images'=>json_encode($calibration_images_paths),
            'completed_by_user_id'=>$emp_id->emp_id,
        ]);
        
        $intervalDay = Machine::where('id',$machine_id)->value('service_interval_days');
        $newServiceDate = now()->addDays($intervalDay);
        Machine::where('id',$machine_id)->update([
            'last_service_date' => now()->format('Y-m-d'),
            'next_service_date' => $newServiceDate->format('Y-m-d'),
            ]);
        return redirect()->route('service.report')->with('success', 'Service report added successfully!');
    }

    public function history(){
        return view('service.history');
    }
}
