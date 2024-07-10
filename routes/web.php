<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/home', function () {
        return view('dashboard');
    });
    Route::resource('/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::resource('/roles', RoleController::class)->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'show' => 'admin.roles.show',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
        
    ]);
    Route::get('/roles/{role}/permissions', [App\Http\Controllers\RoleController::class, 'permissions'])->name('admin.roles.permissions');
    Route::delete('admin/roles/{role}/permissions/{permission}', [RoleController::class, 'destroy_role_permissions'])->name('admin.roles.permissions.destroy');
    Route::resource('/permissions', PermissionController::class)->names([
        'index' => 'admin.permissions.index',
        'create' => 'admin.permissions.create',
        'store' => 'admin.permissions.store',
        'show' => 'admin.permissions.show',
        'edit' => 'admin.permissions.edit',
        'update' => 'admin.permissions.update',
        'destroy' => 'admin.permissions.destroy',
        
    ]);
    // Departments
    Route::resource('/departments', DepartmentController::class)->names([
        'index' => 'admin.departments.index',
        'create' => 'admin.departments.create',
        'store' => 'admin.departments.store',
        'show' => 'admin.departments.show',
        'edit' => 'admin.departments.edit',
        'update' => 'admin.departments.update',
        'destroy' => 'admin.departments.destroy',
    ]);

      // Equipment
      Route::resource('/equipment', EquipmentController::class)->names([
        'index' => 'admin.equipment.index',
        'create' => 'admin.equipment.create',
        'store' => 'admin.equipment.store',
        'show' => 'admin.equipment.show',
        'edit' => 'admin.equipment.edit',
        'update' => 'admin.equipment.update',
        'destroy' => 'admin.equipment.destroy',
    ]);
    
})->middleware('auth');

Auth::routes(); 

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

