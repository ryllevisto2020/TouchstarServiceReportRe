<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\touchStarEmp;
use App\Models\touchstarUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
{
     public function index(Request $request)
    {
        $query = Machine::query();

        // ✅ Status filter
        if ($request->has('status') && $request->status && $request->status !== 'All Statuses') {
            $query->where('status', $request->status);
        }

        // ✅ Location filter
        if ($request->has('location') && $request->location && $request->location !== 'All Locations') {
            $query->where('client_location', $request->location);
        }

        // ✅ Serial Number search (NEW)
        if ($request->has('serial_search') && $request->serial_search) {
            $query->where('serial_number', 'like', '%' . $request->serial_search . '%');
        }

        // ✅ PMS Due filter
        if ($request->has('pms_due') && $request->pms_due && $request->pms_due !== 'Any Time') {
            $now = now();

            switch ($request->pms_due) {
                case 'Next 7 Days':
                    $query->whereBetween('next_service_date', [$now, $now->copy()->addDays(7)]);
                    break;

                case 'Next 30 Days':
                    $query->whereBetween('next_service_date', [$now, $now->copy()->addDays(30)]);
                    break;

                case 'Overdue':
                    $query->whereNotNull('next_service_date') // ✅ prevent null errors
                          ->where('next_service_date', '<', $now);
                    break;
            }
        }

        // ✅ Paginate
        $machines = $query->orderBy('created_at', 'desc')->paginate(12);

        // ✅ Get distinct locations for dropdown
        $locations = Machine::distinct()
            ->whereNotNull('client_location')
            ->pluck('client_location')
            ->filter()
            ->sort()
            ->values();

        // ✅ Get distinct serial numbers for search autocomplete
        $serialNumbers = Machine::distinct()
            ->whereNotNull('serial_number')
            ->pluck('serial_number')
            ->filter()
            ->sort()
            ->values();
        
        $employee_details = touchStarEmp::where('emp_id', Auth::guard('touchstaraccount')->user()->emp_id)->first();

        return view('machines.machine', compact('machines', 'locations', 'serialNumbers','employee_details'));
    }



    public function store(Request $request)
    {
        // Simple validation
        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:machines,serial_number',
            'client_location' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:255',
            'status' => 'required|in:Operational,Maintenance,Standby,Not Operational',
            'installation_date' => 'required|date|before_or_equal:today',
            'last_service_date' => 'required|date|before_or_equal:today',
            'service_interval_days' => 'required|integer|min:1|max:365',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            $data = $request->only([
                'name', 'model', 'serial_number', 'client_location', 
                'region', 'city', 'status', 'installation_date', 
                'last_service_date', 'service_interval_days', 'description'
            ]);

            // Calculate next service date
           $lastServiceDate = Carbon::parse($request->last_service_date);
        $data['next_service_date'] = $lastServiceDate->addDays((int) $request->service_interval_days);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'machine_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $data['image_path'] = $image->storeAs('machines', $imageName, 'public');
            }

            $machine = Machine::create($data);

            // Return JSON for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Medical machine registered successfully!',
                    'machine' => $machine
                ]);
            }

            // Regular form submission - redirect with success message
            return redirect()->route('machines.index')
                ->with('success', 'Medical machine registered successfully!');

        } catch (\Exception $e) {
            \Log::error('Machine creation failed: ' . $e->getMessage());
            
            // Return JSON error for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to register machine. Please try again.'
                ], 500);
            }
            
            // Regular form submission - redirect back with error
            return back()->with('error', 'Failed to register machine. Please try again.')
                ->withInput();
        }
    }
    
    public function edit(Machine $machine)
    {
        return response()->json([
            'id' => $machine->id,
            'name' => $machine->name,
            'model' => $machine->model,
            'serial_number' => $machine->serial_number,
            'client_location' => $machine->client_location,
            'region' => $machine->region,
            'city' => $machine->city,
            'status' => $machine->status,
            'installation_date' => $machine->installation_date->format('Y-m-d'),
            'last_service_date' => $machine->last_service_date->format('Y-m-d'),
            'service_interval_days' => $machine->service_interval_days,
            'description' => $machine->description,
            'image_path' => $machine->image_path ? Storage::url($machine->image_path) : null,
        ]);
    }
    
    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:machines,serial_number,' . $machine->id,
            'client_location' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:255',
            'status' => 'required|in:Operational,Maintenance,Standby,Not Operational',
            'installation_date' => 'required|date|before_or_equal:today',
            'last_service_date' => 'required|date|before_or_equal:today',
            'service_interval_days' => 'required|integer|min:1|max:365',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        
        try {
            $data = $request->only([
                'name', 'model', 'serial_number', 'client_location',
                'region', 'city', 'status', 'installation_date',
                'last_service_date', 'service_interval_days', 'description'
            ]);
            
            // Recalculate next service date
          $lastServiceDate = Carbon::parse($request->last_service_date);
            $data['next_service_date'] = $lastServiceDate->addDays((int) $request->service_interval_days);

            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($machine->image_path) {
                    Storage::disk('public')->delete($machine->image_path);
                }
                
                $image = $request->file('image');
                $imageName = 'machine_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $data['image_path'] = $image->storeAs('machines', $imageName, 'public');
            }
            
            $machine->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Machine updated successfully!',
                'machine' => $machine
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Machine update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update machine. Please try again.'
            ], 500);
        }
    }
    
    public function destroy(Machine $machine)
    {
        try {
            // Delete image file if exists
            if ($machine->image_path) {
                Storage::disk('public')->delete($machine->image_path);
            }
            
            // Delete the machine
            $machine->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Machine deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Machine deletion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete machine. Please try again.'
            ], 500);
        }
    }
    
    public function getMachineDetails(Machine $machine)
    {
        return response()->json([
            'id' => $machine->id,
            'name' => $machine->name,
            'model' => $machine->model,
            'serial_number' => $machine->serial_number,
            'status' => $machine->status,
            'client_location' => $machine->client_location,
            'region' => $machine->region,
            'city' => $machine->city,
            'installation_date' => $machine->installation_date->format('Y-m-d'),
            'last_service_date' => $machine->last_service_date->format('d M Y'),
            'next_service_date' => $machine->next_service_date ? $machine->next_service_date->format('d M Y') : 'Not calculated',
            'service_interval_days' => $machine->service_interval_days,
            'image_path' => $machine->image_path ? Storage::url($machine->image_path) : null,
            'description' => $machine->description ?? 'No description available'
        ]);
    }
     public function getAllMachines()
{
    $machines = Machine::all()->toArray();
    return response()->json(['machines' => $machines]);
}
}