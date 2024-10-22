<?php

namespace App\Http\Controllers;

use App\Events\CalibrationPerformCreated;
use App\Events\CalibrationPerformStatusChanged;
use App\Http\Requests\CalibrationPerformRequest;
use App\Models\CalibrationPerform;
use App\Models\CalibrationPerformDetail;
use App\Models\CalibrationRequest;
use App\Models\SparePart;
use App\Models\User;
use App\Notifications\CalibrationPerformReply;
use App\Notifications\CalibrationPerformStatusChangedNotify;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalibrationPerformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = null)
    {
        //
        $user = Auth::user();
        $calibration_performs = CalibrationPerform::query();

        // For Technicians
        if ($user->hasRole('Technician')) {
            $calibration_performs->where(function (Builder $query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->orWhere('performed_by_id', $user->id)
                    ->orWhereHas('calibrationRequest', function (Builder $query) use ($user) {
                        $query->whereHas('assignments', function (Builder $query2) use ($user) {
                            $query2->where('assigned_to_id', $user->id);
                        });
                    });
            });
        }
        // For Managers
        elseif ($user->hasRole('Manager')) {
            $calibration_performs->where(function (Builder $query) use ($user) {
                $query->where('requester_id', $user->id)
                    ->orWhere('performed_by_id', $user->id);
            });
        }
        // For other roles, fetch all records
        else {
            $calibration_performs = CalibrationPerform::query();
        }
        
        // Apply status filtering if a status is provided
        if (!empty($status)) {
            $calibration_performs->where('status', $status);
        }
        
        // Get the final result
        $calibration_performs = $calibration_performs->get();
        

        return view('calibration_perform.index', compact('calibration_performs'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $calibration_request = CalibrationRequest::find($id);
        $user = Auth::user();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        if (!$calibration_request) {
            return redirect()->route('admin.calibration-request.index')->with('error', 'Calibration request not found');
        }
        $this->authorize('replyWithPerform', $calibration_request);
            return view('calibration_perform.create', compact('calibration_request', 'technicians'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CalibrationPerformRequest $request, $calibration_request_id)
    {
        //
        // Start a database transaction
        DB::beginTransaction();
        try {
            //code...

            $calibration_request = CalibrationRequest::find($calibration_request_id);

            $this->authorize('replyWithPerform', $calibration_request);

            // Create the CalibrationPerform record
            $calibrationPerform = CalibrationPerform::create([
                'calibration_request_id' => $calibration_request_id,
                'service_report' => $request->service_report,
                'technician_id' => $request->technician_id,
                'requester_id' => $calibration_request->requester_id,
                'performed_by_id' => Auth::user()->id,

                'perform_date' => $request->perform_date,
                'status' => 'InProgress',
            ]);
            // Iterate over each spare part and create SparePart and CalibrationPerformDetail records
            foreach ($request->spare_parts as $sparePartData) {
                // Create SparePart record
                if ($sparePartData['name'] != null) {

                    $sparePart = SparePart::create([
                        'name' => $sparePartData['name'],
                        'equipment_id' => $calibration_request->equipment->id
                    ]);


                    // Create CalibrationPerformDetail record
                    CalibrationPerformDetail::create([
                        'calibration_perform_id' => $calibrationPerform->id,
                        'spare_part_id' => $sparePart->id,
                        'quantity' => $sparePartData['qty'],
                        'price' => $sparePartData['price'],
                        'currency' => $sparePartData['currency'],
                        'warranty' => $sparePartData['warranty'],
                        'warranty_unit' => $sparePartData['warranty_unit'],

                    ]);
                }
            }
              // change calibration request status
              $calibration_request->status = 'InProgress';
              $calibration_request->save();
            // Commit the transaction
            DB::commit();
          

            // Send notification to the requester and save it
            $requester = User::find($calibration_request->requester_id);
            $requester->notify(new CalibrationPerformReply($calibrationPerform));

            // Fire event to send realtime notification
            event(new CalibrationPerformCreated($calibrationPerform));

            return redirect()->route('admin.calibration-perform.index')
                ->with('success', 'Calibration perform created successfully.');
        } catch (\Throwable $th) {
            throw $th;
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
        $calibration_perform = CalibrationPerform::find($id);
        if (!$calibration_perform) {
            return redirect()->route('admin.calibration-perform.index')->with('error', 'Calibration Perform not found');
        }
        $this->authorize('view', $calibration_perform);
       
        return view('calibration_perform.show', compact('calibration_perform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $calibrationPerform = CalibrationPerform::with('performDetails.sparePart')->findOrFail($id);
        if (!$calibrationPerform) {
            return redirect()->route('admin.calibration-perform.index')->with('error', 'Calibration Perform not found');
        }
        $this->authorize('update', $calibrationPerform);
        return view('calibration_perform.edit', compact('calibrationPerform'));

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CalibrationPerformRequest $request, $id)
    {

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the CalibrationPerform record by its ID
            $calibrationPerform = CalibrationPerform::findOrFail($id);

            // Update the CalibrationPerform fields
            $calibrationPerform->update([
                'service_report' => $request->service_report,
                'perform_date' => $request->perform_date,
            ]);

            // Delete existing details and spare parts related to this CalibrationPerform
            $calibrationPerform->performDetails()->delete();

            // Iterate over the spare_parts array to create or update spare parts
            if (isset($request->spare_parts)) {
                foreach ($request->spare_parts as $sparePartData) {
                    // Create a new SparePart record
                    if ($sparePartData['name'] != null) {
                        $sparePart = SparePart::create([
                            'name' => $sparePartData['name'],
                            'equipment_id' => $calibrationPerform->calibrationRequest->equipment_id

                        ]);
                    }
                    // // Handle attachments if any
                    // $attachments = [];
                    // if (isset($sparePartData['attachments'])) {
                    //     foreach ($sparePartData['attachments'] as $attachment) {
                    //         $attachments[] = $attachment->store('attachments');
                    //     }
                    // }

                    // Create a new CalibrationPerformDetail record
                    CalibrationPerformDetail::create([
                        'calibration_perform_id' => $calibrationPerform->id,
                        'spare_part_id' => $sparePart->id,
                        'price' => $sparePartData['price'],
                        'quantity' => $sparePartData['qty'],
                        'currency' => $sparePartData['currency'],
                        'warranty' => $sparePartData['warranty'],
                        'warranty_unit' => $sparePartData['warranty_unit'],

                        // 'attachments' => json_encode($attachments),
                    ]);
                }
            }


            // Commit the transaction
            DB::commit();
        
            return redirect()->route('admin.calibration-perform.index')
                ->with('success', 'Calibration perform updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Failed to update calibration perform.']);
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
        $calibrationPerform = CalibrationPerform::find($id);
        $calibration_request = CalibrationRequest::find($calibrationPerform->calibration_request_id);

        $calibrationPerform->delete();

        // return CalibrationRequest status to Pending
        $calibration_request->status = 'Pending';
        $calibration_request->save();

        return redirect()->back()->with('success', 'Calibration Perform Deleted successfully');
    }
    public function changeStatus(Request $request, $id)
    {
        $calibrationPerform = CalibrationPerform::findOrFail($id);
        $user = Auth::user();
        $calibrationPerform->status = $request->status;
        $calibrationPerform->save();
        // change CalibrationRequest status to done
        $calibration_request = CalibrationRequest::find($calibrationPerform->calibration_request_id);
        if($request->status=='Pending'){
            $calibration_request->status = 'Pending';

        }
        else{
            $calibration_request->status = 'Done';

        }

        $calibration_request->save();

        // Send notification to the technician and save it
        $technician = User::find($calibrationPerform->technician_id);
        $technician->notify(new CalibrationPerformStatusChangedNotify($calibrationPerform));

        // Fire event to send realtime notification
        event(new CalibrationPerformStatusChanged($calibrationPerform));

        if (count($calibrationPerform->calibrationRequest->assignments) > 0) {
            // Send notification to the technician manager  foroward request to  and save it

            $technician2 = User::find($calibrationPerform->calibrationRequest->assignments[0]->assigned_to_id);
            $technician2->notify(new CalibrationPerformStatusChangedNotify($calibrationPerform));

            // Fire event to send realtime notification
            event(new CalibrationPerformStatusChanged($calibrationPerform, $technician2->id));
        }
        return redirect()->route('admin.calibration-perform.index')->with('success', 'Status updated successfully.');
    }
}
