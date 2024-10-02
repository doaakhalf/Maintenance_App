<?php

namespace App\Http\Controllers;

use App\Events\AssignBatchRequest;
use App\Events\MaintenanceRequestCreated;
use App\Http\Requests\AssignPatchEquipmentOfDepartmentRequest;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Notifications\AssignBatchRequestNotify;
use App\Notifications\MaintenanceRequestAssigned;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $departments = Department::all();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
           ->orWhere('role_name', 'Manager');
        })->get();
        return view('departements.index',compact('departments','technicians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('departements.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        //
        try{
            $departement=new Department();
            $departement->name=$request->name;
            $departement->number=$request->number;
            $departement->location=$request->location;
            $departement->save();
            return redirect()->route('admin.departments.index')->with('success', 'Department created successfully');

    } catch (\Exception $e) {
        Log::error('Error creating department: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while creating the department')->withInput();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return redirect()->route('admin.departments.index')->with('error', 'Department not found');
        }
       
        return view('departements.edit',compact('department'));


        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, $id)
    {
        //

        try{
            $department=Department::find($id);
            if (!$department) {
                return redirect()->route('admin.departments.index')->with('error', 'Department not found');
            }
            $department->name=$request->name;
            $department->number=$request->number;
            $department->location=$request->location;
            $department->save();
            return redirect()->route('admin.departments.index')->with('success', 'Department Updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the department')->withInput();
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
            $department = Department::find($id);
            if (!$department) {
                return redirect()->route('admin.departments.index')->with('error', 'Department not found');
            }
            if(count($department->equipments) >0){
                return redirect()->route('admin.departments.index')->with('error', 'there are Equipment related to this department');

            }
            $department->delete();
            return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage());
            return redirect()->route('admin.departments.index')->with('error', 'An error occurred while deleting the department');
        }
        
        
        //
    }
    
    public function assignEquipmentOfDepToUser(AssignPatchEquipmentOfDepartmentRequest $request,$department_id)
    {
      
       $assigned_to_id=$request->signed_to_id;
       $departement=Department::find($department_id);
       $batch_id = Str::uuid();
       if($departement){
        $selected_equipment = $departement->equipments->pluck('id'); // This is an array of equipment IDs
        foreach ($selected_equipment as $equipment_id) {
            // Create a maintenance request for each piece of equipment
            $maintenanceRequest=MaintenanceRequest::create([
                'equipment_id' => $equipment_id,
                'signed_to_id' => $assigned_to_id,
                'department_id' => $department_id,
                'requester_id' => Auth::id(), // Assuming the requester is the current user
                'status' => 'Pending', //
                'type' => $request->type, // 
                'name' => $request->name, // 
                'request_type'=>"department",
                'batch_id' => $batch_id // Unify by assigning the same batch ID
           
            ]);
    
            
        }
         // Optionally, send notification to technician
     $technician = User::find($assigned_to_id);
     $technician->notify(new AssignBatchRequestNotify ($selected_equipment->toArray(),$maintenanceRequest));

     // Fire an event for real-time notification (optional)
     event(new AssignBatchRequest($selected_equipment->toArray(),$maintenanceRequest));
     return redirect()->back()->with('success', 'Equipment assigned successfully!');
       }
       else{
        // not found
        return redirect()->back()->with('error', 'Department not found!');
       }
    

    }
}
