<?php

namespace App\Http\Controllers;

use App\Events\MaintenanceRequestCreated;
use App\Events\MaintenanceRequestStatusChanged;
use App\Http\Requests\maintenance_requestRequest;
use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Notifications\MaintenanceRequestAssigned;
use App\Notifications\MaintenanceRequestStatusChangedNotify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=Auth::user();
       
        if($user->hasRole('Technician'))
         $maintenance_requests=MaintenanceRequest::query()->where('signed_to_id',$user->id)->get();
        else
         $maintenance_requests=MaintenanceRequest::all();
        
        return view('maintenance_request.index', compact('maintenance_requests'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $equipment = Equipment::all();
        $users = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
            ->orWhere('role_name', 'Admin');
        })->get();

        return view('maintenance_request.create', compact('equipment','users'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(maintenance_requestRequest $request)
    {
       
        
        try {
            $requester_id=Auth::user()->id;
            $maintenanceRequest = new MaintenanceRequest($request->all());
            $maintenanceRequest->requester_id=$requester_id;
            $maintenanceRequest->save();

             // Send notification to the technician and save it
            $technician = User::find($request->signed_to_id);
            $technician->notify(new MaintenanceRequestAssigned($maintenanceRequest));

                // Fire event to send realtime notification
            event(new MaintenanceRequestCreated($maintenanceRequest));
  
            return redirect()->route('admin.maintenance-requests.index')->with('success', 'Maintenance Request created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating Maintenance Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the Maintenance Request')->withInput();
        }
        
    }
    
    public function getDepartmentByEquipment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment) {
            $department = $equipment->department;
            return response()->json([$department,$equipment]);
        }

        return response()->json(null, 404);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $maintenance_request=MaintenanceRequest::find($id);
        if (!$maintenance_request) {
            return redirect()->route('admin.maintenance-requests.index')->with('error', 'Maintenance Request not found');
        }
        return view('maintenance_request.show', compact('maintenance_request'));
          
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $maintenance_request =  MaintenanceRequest::find($id);
        $equipment = Equipment::all();
        $users = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
            ->orWhere('role_name', 'Admin');
        })->get();
        if(!$maintenance_request)
            return redirect()->route('admin.maintenance-requests.index')->with('error', 'maintenance request not found');
        
        return view('maintenance_request.edit', compact('maintenance_request','equipment','users'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(maintenance_requestRequest $request, $id)
    {
        try {
            $requester_id=Auth::user()->id;
            $maintenanceRequest=MaintenanceRequest::find($id);
            if (!$maintenanceRequest) {
                return redirect()->route('admin.maintenance-requests.index')->with('error', 'Maintenance Request not found');
            }
            $maintenanceRequest->fill($request->all());
            $maintenanceRequest->requester_id=$requester_id;
            $maintenanceRequest->save();

             // Send notification to the technician and save it
            $technician = User::find($request->signed_to_id);
            $technician->notify(new MaintenanceRequestAssigned($maintenanceRequest));

                // Fire event to send realtime notification
            event(new MaintenanceRequestCreated($maintenanceRequest));
            return redirect()->route('admin.maintenance-requests.index')->with('success', 'Maintenance Request updated successfully');
  
        } catch (\Exception $e) {
            Log::error('Error updating Maintenance Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while update the Maintenance Request')->withInput();
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
        //
        $maintenanceRequest=MaintenanceRequest::find($id);
        if($maintenanceRequest->status!='Pending'){
            return redirect()->back()->with('error', 'Cant Delete the Maintenance Request as the status is ' .$maintenanceRequest->status );
        }
        $maintenanceRequest->delete();
        return redirect()->back()->with('success', 'Maintenance Request Deleted successfully');
        
    }
    public function changeStatus(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);

        $maintenanceRequest->status = $request->input('status');
        $maintenanceRequest->save();
        // Send notification to the technician and save it
        $technician = User::find($maintenanceRequest->signed_to_id);
        $technician->notify(new MaintenanceRequestStatusChangedNotify($maintenanceRequest));

        // Fire event to send realtime notification
    event(new MaintenanceRequestStatusChanged($maintenanceRequest));
            
        return redirect()->route('admin.maintenance-requests.index')->with('success', 'Status updated successfully.');
    }
}
