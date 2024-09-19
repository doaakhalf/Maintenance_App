<?php

namespace App\Http\Controllers;

use App\Events\CalibrationRequestCreated;
use App\Http\Requests\calibration_requestRequest;
use App\Models\CalibrationRequest;
use App\Models\CalibrationRequestAssignments;
use App\Models\Equipment;
use App\Models\User;
use App\Notifications\CalibrationRequestAssigned;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalibrationRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('viewAny', CalibrationRequest::class);
        $user = Auth::user();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        if ($user->hasRole('Technician')){
            $forward_requests_id=CalibrationRequestAssignments::where('assigned_to_id',$user->id)->pluck('maintenance_request_id');
           
            $calibration_requests = CalibrationRequest::query()->where('signed_to_id', $user->id)->orWhereIn('id',$forward_requests_id)->get();

        }
        elseif ($user->hasRole('Manager'))
            $calibration_requests = CalibrationRequest::query()->where('signed_to_id', $user->id)->orwhere('requester_id', $user->id)->get();

        else
            $calibration_requests = CalibrationRequest::all();

        return view('calibration_request.index', compact('calibration_requests', 'technicians'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        {
            $this->authorize('create', CalibrationRequest::class);
            $equipment = Equipment::all();
            $users = User::whereHas('role', function (Builder $query) {
                $query->where('role_name', 'Technician')
                    ->orWhere('role_name', 'Admin')
                    ->orWhere('role_name', 'Manager');;
            })->get();
    
    
            return view('calibration_request.create', compact('equipment', 'users'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(calibration_requestRequest $request)
    {
        $this->authorize('create', CalibrationRequest::class);


        try {
            $requester_id = Auth::user()->id;
            $calibrationRequest = new CalibrationRequest($request->all());
            $calibrationRequest->requester_id = $requester_id;
            $calibrationRequest->save();

            // Send notification to the technician and save it
            $technician = User::find($request->signed_to_id);
            $technician->notify(new CalibrationRequestAssigned($calibrationRequest));

            // Fire event to send realtime notification
            event(new CalibrationRequestCreated($calibrationRequest));

            return redirect()->route('admin.calibration-request.index')->with('success', 'Calibration Request created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating Calibration Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the Calibration Request')->withInput();
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
    public function forward_request(Request $request)
    {

        $this->authorize('create', CalibrationRequest::class);


        try {
            $requester_id = Auth::user()->id;
            $maintenanceRequest = CalibrationRequest::find($request->maintenance_request_id);

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
                $existingAssignment = CalibrationRequestAssignments::where($conditions)->first();
                CalibrationRequestAssignments::updateOrCreate(
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
                $technician->notify(new CalibrationRequestAssigned($maintenanceRequest));
                // Fire event to send realtime notification
                event(new CalibrationRequestCreated($maintenanceRequest));
    
              }
            }
         
            return redirect()->route('admin.maintenance-requests.index')->with('success', 'Maintenance Request Forward successfully');
        } catch (\Exception $e) {
            Log::error('Error Forward Maintenance Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while Forward the Maintenance Request')->withInput();
        }
    }
    public function changeStatus(Request $request, $id)
    {
        $maintenanceRequest = CalibrationRequest::findOrFail($id);

        $maintenanceRequest->status = $request->input('status');
        $maintenanceRequest->save();
        // Send notification to the technician and save it
        $technician = User::find($maintenanceRequest->signed_to_id);
        $technician->notify(new CalibrationRequestStatusChangedNotify($maintenanceRequest));

        // Fire event to send realtime notification
        event(new CalibrationRequestStatusChanged($maintenanceRequest));

        return redirect()->route('admin.maintenance-requests.index')->with('success', 'Status updated successfully.');
    }
}
