<?php

namespace App\Http\Controllers;

use App\Events\MaintenancePerformCreated;
use App\Http\Requests\MaintenancePerformRequest;
use App\Models\MaintenancePerform;
use App\Models\MaintenancePerformDetail;
use App\Models\MaintenanceRequest;
use App\Models\SparePart;
use App\Models\User;
use App\Notifications\MaintenancePerformReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaintenancePerformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        if ($user->hasRole('Technician'))
            $maintenance_performs = MaintenancePerform::query()->where('technician_id', $user->id)->get();
        else
            $maintenance_performs = MaintenancePerform::all();

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
        return view('maintenance_perform.create', compact('maintenance_request'));
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
            // Create the MaintenancePerform record
            $maintenancePerform = MaintenancePerform::create([
                'maintenance_request_id' => $maintenance_request_id,
                'service_report' => $request->service_report,
                'technician_id' => $request->technician_id,
                'requester_id' => $maintenance_request->requester_id,
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
                    ]);
                }
            }
            // Commit the transaction
            DB::commit();
            // change maintenance request status
            $maintenance_request->status = 'InProgress';
            $maintenance_request->save();

            // Send notification to the requester and save it
            $requester = User::find($maintenance_request->requester_id);
            $requester->notify(new MaintenancePerformReply($maintenancePerform));

            // Fire event to send realtime notification
            event(new MaintenancePerformCreated($maintenancePerform));

            return redirect()->back()
                ->with('success', 'Maintenance perform created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
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
        return view('maintenance_perform.edit', compact('maintenancePerform'));
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
                        // 'attachments' => json_encode($attachments),
                    ]);
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
         $maintenancePerform=MaintenancePerform::find($id);
         if($maintenancePerform->status!='Pending'){
             return redirect()->back()->with('error', 'Cant Delete the Maintenance Perform as the status is ' .$maintenanceRequest->status );
         }
         $maintenancePerform->delete();
         return redirect()->back()->with('success', 'Maintenance Perform Deleted successfully');
         
    }
}
