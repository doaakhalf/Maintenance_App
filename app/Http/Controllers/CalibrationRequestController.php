<?php

namespace App\Http\Controllers;

use App\Events\CalibrationRequestCreated;
use App\Events\CalibrationRequestStatusChanged;
use App\Http\Requests\calibration_requestRequest;
use App\Models\CalibrationRequest;
use App\Models\CalibrationRequestAssignments;
use App\Models\Equipment;
use App\Models\User;
use App\Notifications\CalibrationRequestAssigned;
use App\Notifications\CalibrationRequestStatusChangedNotify;
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
        if ($user->hasRole('Technician')) {
            $forward_requests_id = CalibrationRequestAssignments::where('assigned_to_id', $user->id)->pluck('calibration_request_id');

            $calibration_requests = CalibrationRequest::query()->where('signed_to_id', $user->id)->orWhereIn('id', $forward_requests_id)->get();
        } elseif ($user->hasRole('Manager'))
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
    { {
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


        // try {
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
        // } catch (\Exception $e) {
        //     Log::error('Error creating Calibration Request: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'An error occurred while creating the Calibration Request')->withInput();
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $calibration_request = CalibrationRequest::find($id);
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        $this->authorize('view', $calibration_request);

        if (!$calibration_request) {
            return redirect()->route('admin.calibration-request.index')->with('error', 'Calibration Request not found');
        }
        return view('calibration_request.show', compact('calibration_request', 'technicians'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $calibration_request =  CalibrationRequest::find($id);
        if (!$calibration_request)
            return redirect()->route('admin.calibration-request.index')->with('error', 'calibration request not found');

        $this->authorize('update', $calibration_request);

        $equipment = Equipment::all();
        $users = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician')
                ->orWhere('role_name', 'Admin')
                ->orWhere('role_name', 'Manager');;
        })->get();



        return view('calibration_request.edit', compact('calibration_request', 'equipment', 'users'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(calibration_requestRequest $request, $id)
    {
        try {
            $requester_id = Auth::user()->id;
            $calibrationRequest = CalibrationRequest::find($id);

            if (!$calibrationRequest) {
                return redirect()->route('admin.calibration-request.index')->with('error', 'Calibration Request not found');
            }
            $this->authorize('update', $calibrationRequest);

            $calibrationRequest->fill($request->all());
            $calibrationRequest->requester_id = $requester_id;
            $calibrationRequest->save();

            // Send notification to the technician and save it
            $technician = User::find($request->signed_to_id);
            $technician->notify(new CalibrationRequestAssigned($calibrationRequest));

            // Fire event to send realtime notification
            event(new CalibrationRequestCreated($calibrationRequest));
            return redirect()->route('admin.calibration-request.index')->with('success', 'Calibration Request updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating Calibration Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while update the Calibration Request')->withInput();
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
        $calibrationRequest = CalibrationRequest::find($id);
        $this->authorize('delete', $calibrationRequest);

        if ($calibrationRequest->status != 'Pending') {
            return redirect()->back()->with('error', 'Cant Delete the Calibration Request as the status is ' . $calibrationRequest->status);
        }
        $calibrationRequest->delete();
        return redirect()->back()->with('success', 'Calibration Request Deleted successfully');
    }
    public function forward_request(Request $request)
    {

        $this->authorize('create', CalibrationRequest::class);


        try {

            $requester_id = Auth::user()->id;
            $calibrationRequest = CalibrationRequest::find($request->calibration_request_id);

            if (!$calibrationRequest) {
                return redirect()->route('admin.calibration-request.index')->with('error', 'Calibration Request not found');
            }
            // Send notification to the technician and save it
            $technician = User::find($request->technician_id);
            if ($technician) {
                // Create an assignment record for the technician

                // The conditions to check for an existing record
                $conditions = [
                    'calibration_request_id' => $calibrationRequest->id,
                    'assigned_by_id' => $requester_id,
                    'assigned_to_id' => $request->technician_id,
                ];

                // Step 2: Check if a record exists with the given conditions
                $existingAssignment = CalibrationRequestAssignments::where($conditions)->first();
                CalibrationRequestAssignments::updateOrCreate(
                    [
                        'calibration_request_id' => $calibrationRequest->id,
                        'assigned_by_id' => $requester_id,
                    ],
                    [
                        // The fields to update or create if the record doesn't exist

                        'assigned_to_id' => $request->technician_id,
                    ]
                );
                if (!$existingAssignment) {
                    $technician->notify(new CalibrationRequestAssigned($calibrationRequest));
                    // Fire event to send realtime notification
                    event(new CalibrationRequestCreated($calibrationRequest));
                }
            }

            return redirect()->route('admin.calibration-request.index')->with('success', 'Calibration Request Forward successfully');
        } catch (\Exception $e) {
            Log::error('Error Forward Calibration Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while Forward the Calibration Request')->withInput();
        }
    }
    public function changeStatus(Request $request, $id)
    {
        $calibrationRequest = CalibrationRequest::findOrFail($id);

        $calibrationRequest->status = $request->input('status');
        $calibrationRequest->save();
        if($calibrationRequest->status=='Pending'){
            $calibrationRequest->calibrationPerform()->delete();
        }
        // Send notification to the technician and save it
        $technician = User::find($calibrationRequest->signed_to_id);
        $technician->notify(new CalibrationRequestStatusChangedNotify($calibrationRequest));

        // Fire event to send realtime notification
        event(new CalibrationRequestStatusChanged($calibrationRequest));

        return redirect()->route('admin.calibration-request.index')->with('success', 'Status updated successfully.');
    }
    public function notification(Request $request, $batch_id)
    {
        $type='Batch';
        $calibration_requests=CalibrationRequest::query()->where('batch_id',$batch_id)
       ->get();
       $user = Auth::user();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        if($calibration_requests &&($user->hasRole('Admin')||$user->id==$calibration_requests[0]->signed_to_id )){
            return view('calibration_request.index', compact('calibration_requests', 'technicians','type'));
        }
        else{
            if(!$calibration_requests){
                return redirect()->back()->with('error', 'Batch Not Exist');


            }
            else{
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
        }
    }
}
