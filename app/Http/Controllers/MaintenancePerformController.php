<?php

namespace App\Http\Controllers;

use App\Events\MaintenancePerformCreated;
use App\Events\MaintenancePerformStatusChanged;
use App\Http\Requests\MaintenancePerformRequest;
use App\Models\MaintenancePerform;
use App\Models\MaintenancePerformDetail;
use App\Models\MaintenanceRequest;
use App\Models\SparePart;
use App\Models\User;
use App\Notifications\MaintenancePerformReply;
use App\Notifications\MaintenancePerformStatusChangedNotify;
use App\Notifications\MaintenanceRequestStatusChangedNotify;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class MaintenancePerformController extends Controller
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
        $maintenance_performs = MaintenancePerform::query();

        // For Technicians
        if ($user->hasRole('Technician')) {
            $maintenance_performs->where(function (Builder $query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->orWhere('performed_by_id', $user->id)
                    ->orWhereHas('maintenanceRequest', function (Builder $query) use ($user) {
                        $query->whereHas('assignments', function (Builder $query2) use ($user) {
                            $query2->where('assigned_to_id', $user->id);
                        });
                    });
            });
        }
        // For Managers
        elseif ($user->hasRole('Manager')) {
            $maintenance_performs->where(function (Builder $query) use ($user) {
                $query->where('requester_id', $user->id)
                    ->orWhere('performed_by_id', $user->id);
            });
        }
        // For other roles, fetch all records
        else {
            $maintenance_performs = MaintenancePerform::query();
        }
        
        // Apply status filtering if a status is provided
        if (!empty($status)) {
            $maintenance_performs->where('status', $status);
        }
        
        // Get the final result
        $maintenance_performs = $maintenance_performs->get();
        

        return view('maintenance_perform.index', compact('maintenance_performs'));
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
        $maintenance_request = MaintenanceRequest::find($id);
        $user = Auth::user();
        $technicians = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Technician');
        })->get();
        if (!$maintenance_request) {
            return redirect()->route('admin.maintenance-requests.index')->with('error', 'Maintenance request not found');
        }
        if ($maintenance_request->status == 'Pending' && ($user->hasRole('Admin') || ($user->hasRole('Manager') && $maintenance_request->signed_to_id == $user->id || $user->hasRole('Manager') && $maintenance_request->requester_id == $user->id  || ($user->hasRole('Technician') && ($maintenance_request->signed_to_id == $user->id || $maintenance_request->assignments[0]->assigned_to_id == $user->id))))) {
            return view('maintenance_perform.create', compact('maintenance_request', 'technicians'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaintenancePerformRequest $request, $maintenance_request_id)
    {
        //
        // Start a database transaction
        DB::beginTransaction();
        try {
            //code...

            $maintenance_request = MaintenanceRequest::find($maintenance_request_id);

            $this->authorize('replyWithPerform', $maintenance_request);

            // Create the MaintenancePerform record
            $maintenancePerform = MaintenancePerform::create([
                'maintenance_request_id' => $maintenance_request_id,
                'service_report' => $request->service_report,
                'technician_id' => $request->technician_id,
                'requester_id' => $maintenance_request->requester_id,
                'performed_by_id' => Auth::user()->id,

                'perform_date' => $request->perform_date,
                'status' => 'InProgress',
            ]);
            // Iterate over each spare part and create SparePart and MaintenancePerformDetail records
            foreach ($request->spare_parts as $sparePartData) {
                // Create SparePart record
                if ($sparePartData['name'] != null) {

                    $sparePart = SparePart::create([
                        'name' => $sparePartData['name'],
                        'equipment_id' => $maintenance_request->equipment->id
                    ]);


                    // Create MaintenancePerformDetail record
                    MaintenancePerformDetail::create([
                        'maintenance_perform_id' => $maintenancePerform->id,
                        'spare_part_id' => $sparePart->id,
                        'quantity' => $sparePartData['qty'],
                        'price' => $sparePartData['price'],
                        'currency' => $sparePartData['currency'],
                        'warranty' => $sparePartData['warranty'],
                        'warranty_unit' => $sparePartData['warranty_unit'],

                    ]);
                }
            }
              // change maintenance request status
              $maintenance_request->status = 'InProgress';
              $maintenance_request->save();
            // Commit the transaction
            DB::commit();
          

            // Send notification to the requester and save it
            $requester = User::find($maintenance_request->requester_id);
            $requester->notify(new MaintenancePerformReply($maintenancePerform));

            // Fire event to send realtime notification
            event(new MaintenancePerformCreated($maintenancePerform));

            return redirect()->route('admin.maintenance-perform.index')
                ->with('success', 'Maintenance perform created successfully.');
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
        $maintenance_perform = MaintenancePerform::find($id);
        $this->authorize('view', $maintenance_perform);
        if (!$maintenance_perform) {
            return redirect()->route('admin.maintenance-perform.index')->with('error', 'Maintenance Perform not found');
        }
        return view('maintenance_perform.show', compact('maintenance_perform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $maintenancePerform = MaintenancePerform::with('performDetails.sparePart')->findOrFail($id);
        if (!$maintenancePerform) {
            return redirect()->route('admin.maintenance-perform.index')->with('error', 'Maintenance Perform not found');
        }
        $this->authorize('update', $maintenancePerform);
        return view('maintenance_perform.edit', compact('maintenancePerform'));

        // if (Auth::user()->hasRole('Admin')  || (Auth::user()->id == $maintenancePerform->technician_id && $maintenancePerform->performed_by_id == Auth::user()->id)) {
        //     return view('maintenance_perform.edit', compact('maintenancePerform'));
        // } else
        //     throw new AuthorizationException('You are not authorized to access this resource.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaintenancePerformRequest $request, $id)
    {

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the MaintenancePerform record by its ID
            $maintenancePerform = MaintenancePerform::findOrFail($id);

            // Update the MaintenancePerform fields
            $maintenancePerform->update([
                'service_report' => $request->service_report,
                'perform_date' => $request->perform_date,
            ]);

            // Delete existing details and spare parts related to this MaintenancePerform
            $maintenancePerform->performDetails()->delete();

            // Iterate over the spare_parts array to create or update spare parts
            if (isset($request->spare_parts)) {
                foreach ($request->spare_parts as $sparePartData) {
                    // Create a new SparePart record
                    if ($sparePartData['name'] != null) {
                        $sparePart = SparePart::create([
                            'name' => $sparePartData['name'],
                            'equipment_id' => $maintenancePerform->maintenanceRequest->equipment_id

                        ]);
                    }
                    // // Handle attachments if any
                    // $attachments = [];
                    // if (isset($sparePartData['attachments'])) {
                    //     foreach ($sparePartData['attachments'] as $attachment) {
                    //         $attachments[] = $attachment->store('attachments');
                    //     }
                    // }

                    // Create a new MaintenancePerformDetail record
                    MaintenancePerformDetail::create([
                        'maintenance_perform_id' => $maintenancePerform->id,
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
        
            return redirect()->route('admin.maintenance-perform.index')
                ->with('success', 'Maintenance perform updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Failed to update maintenance perform.']);
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
        $maintenancePerform = MaintenancePerform::find($id);
        $maintenance_request = MaintenanceRequest::find($maintenancePerform->maintenance_request_id);

        // if ($maintenancePerform->status != 'Pending') {
        //     return redirect()->back()->with('error', 'Cant Delete the Maintenance Perform as the status is ' . $maintenancePerform->status);
        // }
        $maintenancePerform->delete();
        // return MaintenanceRequest status to Pending

        $maintenance_request->status = 'Pending';
        $maintenance_request->save();

        return redirect()->back()->with('success', 'Maintenance Perform Deleted successfully');
    }
    public function changeStatus(Request $request, $id)
    {
        $maintenancePerform = MaintenancePerform::findOrFail($id);
        $user = Auth::user();
        $maintenancePerform->status = $request->status;
        $maintenancePerform->save();
        // change MaintenanceRequest status to done
        $maintenance_request = MaintenanceRequest::find($maintenancePerform->maintenance_request_id);
        if($request->status=='Pending'){
            $maintenance_request->status = 'Pending';

        }
        else{
            $maintenance_request->status = 'Done';

        }

        $maintenance_request->save();

        // Send notification to the technician and save it
        $technician = User::find($maintenancePerform->technician_id);
        $technician->notify(new MaintenancePerformStatusChangedNotify($maintenancePerform));

        // Fire event to send realtime notification
        event(new MaintenancePerformStatusChanged($maintenancePerform));

        if (count($maintenancePerform->maintenanceRequest->assignments) > 0) {
            // Send notification to the technician manager  foroward request to  and save it

            $technician2 = User::find($maintenancePerform->maintenanceRequest->assignments[0]->assigned_to_id);
            $technician2->notify(new MaintenancePerformStatusChangedNotify($maintenancePerform));

            // Fire event to send realtime notification
            event(new MaintenancePerformStatusChanged($maintenancePerform, $technician2->id));
        }
        return redirect()->route('admin.maintenance-perform.index')->with('success', 'Status updated successfully.');
    }
}
