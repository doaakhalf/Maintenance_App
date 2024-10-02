<?php

namespace App\Http\Controllers;

use App\Events\MaintenanceRequestCreated;
use App\Events\MaintenanceRequestStatusChanged;
use App\Http\Requests\maintenance_requestRequest;
use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceRequestAssignments;
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
        
        $this->authorize('viewAny', MaintenanceRequest::class);
        $user = Auth::user();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        if ($user->hasRole('Technician')){
            $forward_requests_id=MaintenanceRequestAssignments::where('assigned_to_id',$user->id)->pluck('maintenance_request_id');
           
            $maintenance_requests = MaintenanceRequest::query()->where('signed_to_id', $user->id)->orWhereIn('id',$forward_requests_id)->get();

        }
        elseif ($user->hasRole('Manager'))
            $maintenance_requests = MaintenanceRequest::query()->where('signed_to_id', $user->id)->orwhere('requester_id', $user->id)->get();

        else
            $maintenance_requests = MaintenanceRequest::all();

        return view('maintenance_request.index', compact('maintenance_requests', 'technicians'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', MaintenanceRequest::class);
        $equipment = Equipment::all();
        $users = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
                ->orWhere('role_name', 'Admin')
                ->orWhere('role_name', 'Manager');;
        })->get();


        return view('maintenance_request.create', compact('equipment', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(maintenance_requestRequest $request)
    {
        $this->authorize('create', MaintenanceRequest::class);


        try {
            $requester_id = Auth::user()->id;
            $maintenanceRequest = new MaintenanceRequest($request->all());
            $maintenanceRequest->requester_id = $requester_id;
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
    public function forward_request(Request $request)
    {

        $this->authorize('create', MaintenanceRequest::class);


        try {
            $requester_id = Auth::user()->id;
            $maintenanceRequest = MaintenanceRequest::find($request->maintenance_request_id);

            if (!$maintenanceRequest) {
                return redirect()->route('admin.maintenance-requests.index')->with('error', 'Maintenance Request not found');
            }
            // Send notification to the technician and save it
            $technician = User::find($request->technician_id);
            if ($technician) {
                // Create an assignment record for the technician
             
                 // The conditions to check for an existing record
                $conditions = [
                    'maintenance_request_id' => $maintenanceRequest->id,
                    'assigned_by_id' => $requester_id,
                    'assigned_to_id' => $request->technician_id,
                ];
                
                // Step 2: Check if a record exists with the given conditions
                $existingAssignment = MaintenanceRequestAssignments::where($conditions)->first();
                MaintenanceRequestAssignments::updateOrCreate(
                   [
                    'maintenance_request_id' => $maintenanceRequest->id,
                    'assigned_by_id' => $requester_id,
                   ],
                    [
                        // The fields to update or create if the record doesn't exist
                      
                        'assigned_to_id' => $request->technician_id,
                    ]
                );
              if(!$existingAssignment){
                $technician->notify(new MaintenanceRequestAssigned($maintenanceRequest));
                // Fire event to send realtime notification
                event(new MaintenanceRequestCreated($maintenanceRequest));
    
              }
            }
         
            return redirect()->route('admin.maintenance-requests.index')->with('success', 'Maintenance Request Forward successfully');
        } catch (\Exception $e) {
            Log::error('Error Forward Maintenance Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while Forward the Maintenance Request')->withInput();
        }
    }

    public function getDepartmentByEquipment($id)
    {
        $equipment = Equipment::find($id);
        if ($equipment) {
            $department = $equipment->department;
            return response()->json([$department, $equipment]);
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
        $maintenance_request = MaintenanceRequest::find($id);
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        $this->authorize('view', $maintenance_request);

        if (!$maintenance_request) {
            return redirect()->route('admin.maintenance-requests.index')->with('error', 'Maintenance Request not found');
        }
        return view('maintenance_request.show', compact('maintenance_request','technicians'));
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
        if (!$maintenance_request)
            return redirect()->route('admin.maintenance-requests.index')->with('error', 'maintenance request not found');

        $this->authorize('update', $maintenance_request);

        $equipment = Equipment::all();
        $users = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
                ->orWhere('role_name', 'Admin')
                ->orWhere('role_name', 'Manager');;
        })->get();



        return view('maintenance_request.edit', compact('maintenance_request', 'equipment', 'users'));
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
            $requester_id = Auth::user()->id;
            $maintenanceRequest = MaintenanceRequest::find($id);

            if (!$maintenanceRequest) {
                return redirect()->route('admin.maintenance-requests.index')->with('error', 'Maintenance Request not found');
            }
            $this->authorize('update', $maintenanceRequest);

            $maintenanceRequest->fill($request->all());
            $maintenanceRequest->requester_id = $requester_id;
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
        $maintenanceRequest = MaintenanceRequest::find($id);
        $this->authorize('delete', $maintenanceRequest);

        if ($maintenanceRequest->status != 'Pending') {
            return redirect()->back()->with('error', 'Cant Delete the Maintenance Request as the status is ' . $maintenanceRequest->status);
        }
        $maintenanceRequest->delete();
        return redirect()->back()->with('success', 'Maintenance Request Deleted successfully');
    }
    public function changeStatus(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);

        $maintenanceRequest->status = $request->input('status');
        $maintenanceRequest->save();
        if($maintenanceRequest->status=='Pending'){
            $maintenanceRequest->maintenancePerforms()->delete();
        }
        // Send notification to the technician and save it
        $technician = User::find($maintenanceRequest->signed_to_id);
        $technician->notify(new MaintenanceRequestStatusChangedNotify($maintenanceRequest));

        // Fire event to send realtime notification
        event(new MaintenanceRequestStatusChanged($maintenanceRequest));

        return redirect()->route('admin.maintenance-requests.index')->with('success', 'Status updated successfully.');
    }
    public function notification(Request $request, $batch_id)
    {
        $type='Batch';
        $maintenance_requests=MaintenanceRequest::query()->where('batch_id',$batch_id)
       ->get();
       $user = Auth::user();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        if($maintenance_requests &&($user->hasRole('Admin')||$user->id==$maintenance_requests[0]->signed_to_id )){
            return view('maintenance_request.index', compact('maintenance_requests', 'technicians','type'));
        }
        else{
            if(!$maintenance_requests){
                return redirect()->back()->with('error', 'Batch Not Exist');


            }
            else{
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
        }
    }

    
}
