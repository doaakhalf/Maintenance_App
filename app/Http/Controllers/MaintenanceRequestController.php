<?php

namespace App\Http\Controllers;

use App\Http\Requests\maintenance_requestRequest;
use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipment = Equipment::all();
        return view('maintenance_request.index', compact('equipment'));
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
            $query->where('role_name', 'Technician');
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

            return redirect()->route('admin.maintenance-requests.index')->with('success', 'Maintenance Request created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating Maintenance Request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the Maintenance Request')->withInput();
        }
        //
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
}
