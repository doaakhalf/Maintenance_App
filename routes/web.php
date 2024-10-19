<?php

use App\Http\Controllers\CalibrationPerformController;
use App\Http\Controllers\CalibrationRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\MaintenancePerformController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware(['auth', 'role:Admin,Manager,Technician'])->prefix('admin')->group(function () {

    Route::get('/home', function () {
        $user = Auth::user();
        if ($user->hasRole('Technician')) {
            return view('dashboard_technician');
        } else
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
    ])->middleware(['auth', 'role:Admin,Manager']);;
    Route::post('/departments/{id}/assign', [App\Http\Controllers\DepartmentController::class, 'assignEquipmentOfDepToUser'])->name('admin.departments.assign');
    Route::post('/departments/{id}/assign-calibration', [App\Http\Controllers\DepartmentController::class, 'assignEquipmentOfDepToUserForCalibration'])->name('admin.departments.assign-calibration');

    // Equipment
    Route::resource('/equipment', EquipmentController::class)->names([
        'index' => 'admin.equipment.index',
        'create' => 'admin.equipment.create',
        'store' => 'admin.equipment.store',
        'show' => 'admin.equipment.show',
        'edit' => 'admin.equipment.edit',
        'update' => 'admin.equipment.update',
        'destroy' => 'admin.equipment.destroy',
    ])->middleware(['auth', 'role:Admin,Manager']);;
    Route::post('/equipment/import', [App\Http\Controllers\EquipmentController::class, 'import'])->name('admin.equipment.import');
    Route::post('/equipment/assign', [App\Http\Controllers\EquipmentController::class, 'assignToUser'])->name('admin.equipment.assign');
    
    Route::get('/equipment/ppm/all', [App\Http\Controllers\EquipmentController::class, 'ppm_equip'])->name('admin.equipment.ppm')->middleware(['auth', 'role:Admin,Manager']);

    // Maintenance Request
    Route::resource('/maintenance-requests', MaintenanceRequestController::class)->names([
        'index' => 'admin.maintenance-requests.index',
        'create' => 'admin.maintenance-requests.create',
        'store' => 'admin.maintenance-requests.store',
        'show' => 'admin.maintenance-requests.show',
        'edit' => 'admin.maintenance-requests.edit',
        'update' => 'admin.maintenance-requests.update',
        'destroy' => 'admin.maintenance-requests.destroy',
    ])->middleware(['auth', 'role:Admin,Manager,Technician']);
    Route::get('/departments/equipment/{id}', [MaintenanceRequestController::class, 'getDepartmentByEquipment']);
    Route::patch('/maintenance-requests/{id}/change-status', [MaintenanceRequestController::class, 'changeStatus'])->name('admin.maintenance-requests.change-status')->middleware(['auth', 'role:Admin,Manager']);;
    Route::post('/maintenance-requests/forward-request', [MaintenanceRequestController::class, 'forward_request'])->name('admin.maintenance-requests.forward-request')->middleware(['auth', 'role:Admin,Manager']);;
    // Batch requests page
    Route::get('/maintenance-requests/{batch_id}/notification/list', [MaintenanceRequestController::class, 'notification'])->name('admin.requests.patch.list');


    // Maintenance Perform
    Route::get('/maintenance-perform/{status?}', [MaintenancePerformController::class, 'index'])->name('admin.maintenance-perform.index')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;

    //   show
    Route::get('/maintenance-perform/show/{id}', [MaintenancePerformController::class, 'show'])->name('admin.maintenance-perform.show')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;

    // create 
    Route::get('/maintenance-perform/{maintenance_request_id}/create', [MaintenancePerformController::class, 'create'])->name('admin.maintenance-perform.create')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);

    Route::post('/maintenance-perform/{maintenance_request_id}/store', [MaintenancePerformController::class, 'store'])->name('admin.maintenance-perform.store')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);
    // edit
    Route::get('/maintenance-perform/edit/{id}', [MaintenancePerformController::class, 'edit'])->name('admin.maintenance-perform.edit')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;

    Route::put('/maintenance-perform/update/{id}', [MaintenancePerformController::class, 'update'])->name('admin.maintenance-perform.update')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;
    Route::delete('/maintenance-perform/delete/{id}', [MaintenancePerformController::class, 'destroy'])->name('admin.maintenance-perform.destroy')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;
    Route::patch('/maintenance-perform/{id}/change-status', [MaintenancePerformController::class, 'changeStatus'])->name('admin.maintenance-perform.change-status')->middleware(['auth', 'role:Admin,Manager']);


    // Calibration Request 
    
    Route::resource('/calibration-request',CalibrationRequestController::class)->names([
        'index' => 'admin.calibration-request.index',
        'create' => 'admin.calibration-request.create',
        'store' => 'admin.calibration-request.store',
        'show' => 'admin.calibration-request.show',
        'edit' => 'admin.calibration-request.edit',
        'update' => 'admin.calibration-request.update',
        'destroy' => 'admin.calibration-request.destroy',
    ])->middleware(['auth', 'role:Admin,Manager,Technician']);
    Route::patch('/calibration-request/{id}/change-status', [CalibrationRequestController::class, 'changeStatus'])->name('admin.calibration-request.change-status')->middleware(['auth', 'role:Admin,Manager']);;
    Route::post('/calibration-request/forward-request', [CalibrationRequestController::class, 'forward_request'])->name('admin.calibration-request.forward-request')->middleware(['auth', 'role:Admin,Manager']);;
    // Batch requests page
    Route::get('/calibration-request/{batch_id}/notification/list', [CalibrationRequestController::class, 'notification'])->name('admin.calibration_request.batch.list');


    // calibration Perform
    Route::get('/calibration-perform/{status?}', [CalibrationPerformController::class, 'index'])->name('admin.calibration-perform.index')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;

    //   show
    Route::get('/calibration-perform/show/{id}', [CalibrationPerformController::class, 'show'])->name('admin.calibration-perform.show')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;

    // create 
    Route::get('/calibration-perform/{maintenance_request_id}/create', [CalibrationPerformController::class, 'create'])->name('admin.calibration-perform.create')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);

    Route::post('/calibration-perform/{maintenance_request_id}/store', [CalibrationPerformController::class, 'store'])->name('admin.calibration-perform.store')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);
    // edit
    Route::get('/calibration-perform/edit/{id}', [CalibrationPerformController::class, 'edit'])->name('admin.calibration-perform.edit')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;

    Route::put('/calibration-perform/update/{id}', [CalibrationPerformController::class, 'update'])->name('admin.calibration-perform.update')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;
    Route::delete('/calibration-perform/delete/{id}', [CalibrationPerformController::class, 'destroy'])->name('admin.calibration-perform.destroy')
        ->middleware(['auth', 'role:Admin,Technician,Manager']);;
    Route::patch('/calibration-perform/{id}/change-status', [CalibrationPerformController::class, 'changeStatus'])->name('admin.calibration-perform.change-status')->middleware(['auth', 'role:Admin,Manager']);


   
})->middleware('auth');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// CLEAR CACHE
Route::get('/artisan/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Done - cache are cleared";
});

// CLEAR ROUTES
Route::get('/artisan/route-cache', function () {
    Artisan::call('route:cache');
    return "Done - routes cache are cleared";
});

// CLEAR VIEWS
Route::get('/artisan/views-clear', function () {
    Artisan::call('view:clear');
    return "Done - views are cleared from cache";
});
