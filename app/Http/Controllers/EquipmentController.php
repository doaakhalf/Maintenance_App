<?php

namespace App\Http\Controllers;

use App\Http\Requests\equipmentRequest;
use App\Imports\EquipmentImport;
use App\Models\Department;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipment = Equipment::all();
        return view('equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments=Department::all();
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
            if(!$equipment)
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
        $departments=Department::all();

        if(!$equipment)
            return redirect()->route('admin.equipment.index')->with('error', 'Equipment not found');
        
        return view('equipment.edit', compact('equipment','departments'));
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
            Log::error('Error creating equipment: ' . $e->getMessage());
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
          
            $equipment->delete();
            return redirect()->route('admin.equipment.index')->with('success', 'Equipment deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting equipment: ' . $e->getMessage());
            return redirect()->route('admin.equipment.index')->with('error', 'An error occurred while deleting the Equipment');
        }
    }
}
