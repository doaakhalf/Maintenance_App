<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index',compact('roles'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions=Permission::all();
        return view('roles.create',compact('permissions'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try{
            $role = Role::create(['role_name' => $request->role_name]);
            $role->permissions()->sync($request->permissions);
            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');

        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the role')->withInput();
        }
    }


    /**
     * Display the role permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissions($role_id)
    {
        $role=Role::find($role_id);
        if(!$role){
            return redirect()->route('admin.roles.index')->with('error', 'Role not found');
        }
        $permissions=Permission::all();
        
        return view('roles.permissions',compact('permissions','role'));
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
        
            $role=Role::find($id);
            if(!$role){
                return redirect()->route('admin.roles.index')->with('error', 'Role not found');
            }
            $permissions=Permission::all();
            
            return view('roles.edit',compact('permissions','role'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
       
        try{
            $role=Role::find($id);
            if (!$role) {
                return redirect()->route('admin.roles.index')->with('error', 'role not found');
            }
            $role->update(['role_name' => $request->role_name]);
            $role->permissions()->sync($request->permissions);

            return redirect()->route('admin.roles.index')->with('success', 'role Updated successfully');

        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the role')->withInput();
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
            $role = role::find($id);
            if (!$role) {
                return redirect()->route('admin.roles.index')->with('error', 'role not found');
            }
            // Manually delete related records
            $role->permissions()->detach();
            $role->delete();
            return redirect()->route('admin.roles.index')->with('success', 'role deleted successfully');
            

        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return redirect()->route('admin.roles.index')->with('error', 'An error occurred while deleting the role');
        }
        
    }
    public function destroy_role_permissions($role_id,$permission_id)
    {
        
        try {
            $role = role::find($role_id);
            $permission = Permission::find($permission_id);

            if (!$role &&  !$permission ) {
                return redirect()->route('admin.roles.index')->with('error', 'role not found');
            }
            // Manually delete permission records
            $role->permissions()->detach($permission_id);
            return redirect()->route('admin.roles.permissions',$role_id)->with('success', 'permission removed successfully');


        } catch (\Exception $e) {
            Log::error('Error deleting permission: ' . $e->getMessage());
            return redirect()->route('admin.roles.permissions',$role_id)->with('error', 'An error occurred while deleting the role');
        }
        
    }
}
