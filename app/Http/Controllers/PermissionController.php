<?php

namespace App\Http\Controllers;

use App\Http\Requests\permissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $permissions = Permission::all();
        return view('permissions.index',compact('permissions'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions=Permission::all();
        return view('permissions.create',compact('permissions'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(permissionRequest $request)
    {
        try{
            $permission = permission::create(['permission_name' => $request->permission_name]);
            return redirect()->route('admin.permissions.index')->with('success', 'permission created successfully.');

        } catch (\Exception $e) {
            Log::error('Error creating permission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the permission')->withInput();
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
        
            $permission=permission::find($id);
            if(!$permission){
                return redirect()->route('admin.permissions.index')->with('error', 'permission not found');
            }
           
            
            return view('permissions.edit',compact('permission'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(permissionRequest $request, $id)
    {
       
        // try{
            $permission=permission::find($id);
            if (!$permission) {
                return redirect()->route('admin.permissions.index')->with('error', 'permission not found');
            }
            $permission->update(['permission_name' => $request->permission_name]);
            return redirect()->route('admin.permissions.index')->with('success', 'permission Updated successfully');

        // } catch (\Exception $e) {
        //     Log::error('Error updating permission: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'An error occurred while updating the permission')->withInput();
        // }
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
            $permission = permission::find($id);
            if (!$permission) {
                return redirect()->route('admin.permissions.index')->with('error', 'permission not found');
            }
            $permissionRolesCount=$permission->roles->count();
           if ($permissionRolesCount>0) {
                return redirect()->route('admin.permissions.index')->with('error', 'Cant Delete Permission as There is a role Use it');
            }
            $permission->delete();
            return redirect()->route('admin.permissions.index')->with('success', 'permission deleted successfully');
            

        } catch (\Exception $e) {
            Log::error('Error deleting permission: ' . $e->getMessage());
            return redirect()->route('admin.permissions.index')->with('error', 'An error occurred while deleting the permission');
        }
        
    }
   
}
