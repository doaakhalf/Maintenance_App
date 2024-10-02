<?php

namespace App\Http\Controllers;

use App\Events\AssignBatchRequest;
use App\Events\MaintenanceRequestCreated;
use App\Http\Requests\AssignPatchEquipmentRequest;
use App\Http\Requests\equipmentRequest;
use App\Imports\EquipmentImport;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Notifications\AssignBatchRequestNotify;
use App\Notifications\MaintenanceRequestAssigned;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipment = Equipment::all()->sortDesc();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
                ->orWhere('role_name', 'Manager');
        })->get();

        return view('equipment.index', compact('equipment', 'technicians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        return view('equipment.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(equipmentRequest $request)
    {
        try {
            $equipment = new Equipment($request->all());
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/equipments', 'public');
                $equipment->image = $path;
            }
            $equipment->save();

            return redirect()->route('admin.equipment.index')->with('success', 'Equipment created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating equipment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the equipment')->withInput();
        }
    }
    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        try {
            Excel::import(new EquipmentImport, $request->file('file'));

            return redirect()->route('admin.equipment.index')->with('success', 'Equipments imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            // Optionally, log or process the validation failures
            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors()) . " (Attribute: {$failure->attribute()})";
            }

            return back()->withErrors($errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $equipment =  Equipment::find($id);
        if (!$equipment)
            return redirect()->route('admin.equipment.index')->with('error', 'Equipment not found');

        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipment =  Equipment::find($id);
        $departments = Department::all();

        if (!$equipment)
            return redirect()->route('admin.equipment.index')->with('error', 'Equipment not found');

        return view('equipment.edit', compact('equipment', 'departments'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(equipmentRequest $request, $id)
    {
        try {
            $equipment = Equipment::find($id);
            if (!$equipment) {
                return redirect()->route('admin.equipment.index')->with('error', 'Equipment not found');
            }
            $equipment->fill($request->all());
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/equipments', 'public');
                $equipment->image = $path;
            }
            $equipment->save();

            return redirect()->route('admin.equipment.index')->with('success', 'Equipment created successfully');
        } catch (\Exception $e) {
            Log::error('Error Updating equipment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while update the equipment')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $equipment = Equipment::find($id);
            if (!$equipment) {
                return redirect()->route('admin.equipment.index')->with('error', 'Equipment not found');
            }
            if (!$equipment->maintenanceRequests()->exists() && !$equipment->calibrationRequests()->exists() && !$equipment->sparePartRequests()->exists() && !$equipment->spareParts()->exists()) {
                $equipment->delete();
                return redirect()->route('admin.equipment.index')->with('success', 'Equipment deleted successfully');
            } else {
                return redirect()->route('admin.equipment.index')->with('error', 'cant delete this Equipment as it related with other records');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting equipment: ' . $e->getMessage());
            return redirect()->route('admin.equipment.index')->with('error', 'An error occurred while deleting the Equipment');
        }
    }
    public function assignToUser(AssignPatchEquipmentRequest $request)
    {

        $assigned_to_id = $request->signed_to_id;
        $selected_equipment = explode(',', $request->selected_items);
        $batch_id = Str::uuid();

        foreach ($selected_equipment as $equipment_id) {
            // Create a new maintenance request for each equipment
            $maintenanceRequest = new MaintenanceRequest();
            $maintenanceRequest->equipment_id = $equipment_id; // Assign the equipment ID
            $maintenanceRequest->signed_to_id = $assigned_to_id; // Assign to the technician
            $maintenanceRequest->status = 'Pending'; // Set default status, e.g., pending
            $maintenanceRequest->requester_id = Auth::id(); // Requester's ID
            $maintenanceRequest->name = $request->name;
            $maintenanceRequest->type = $request->type;
            $maintenanceRequest->request_type = 'equipment';
            $maintenanceRequest->batch_id = $batch_id; // Unify by assigning the same batch ID

            $maintenanceRequest->save();
        }
        // Optionally, send notification to technician
        $technician = User::find($assigned_to_id);
        $technician->notify(new AssignBatchRequestNotify($selected_equipment, $maintenanceRequest));

        // Fire an event for real-time notification (optional)
        event(new AssignBatchRequest($selected_equipment, $maintenanceRequest));
        return redirect()->back()->with('success', 'Maintenance requests Patch created successfully for the selected equipment.');
    }
    public function ppm_equip(Request $request)
    {
        $type='ppm';
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
                ->orWhere('role_name', 'Manager');
        })->get();
        // Current date
        $today = Carbon::today();

        // Query to get all equipment that needs maintenance today
        $equipmentDueForMaintenance = Equipment::where(function ($query) use ($today) {
            $query->where(function ($q) use ($today) {
                // For 'Month' ppm_unit
                $q->where('ppm_unit', 'Month')
                    ->whereRaw("DATE(created_at + INTERVAL ppm MONTH) <= ?", [$today]);
            })->orWhere(function ($q) use ($today) {
                // For 'Day' ppm_unit
                $q->where('ppm_unit', 'Day')
                    ->whereRaw("DATE(created_at + INTERVAL ppm DAY) <= ?", [$today]);
            })->orWhere(function ($q) use ($today) {
                // For 'Year' ppm_unit
                $q->where('ppm_unit', 'Year')
                    ->whereRaw("DATE(created_at + INTERVAL ppm YEAR) <= ?", [$today]);
            });
        })->get();
      

        return view('equipment.index',[
            'equipment' => $equipmentDueForMaintenance,
            'technicians' => $technicians,
            'type'=>$type
        ]);
    }
}
