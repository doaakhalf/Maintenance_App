<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $departments = Department::all();
        return view('departements.index',compact('departments'));
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

            $department->delete();
            return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage());
            return redirect()->route('admin.departments.index')->with('error', 'An error occurred while deleting the department');
        }
        
        
        //
    }
}
