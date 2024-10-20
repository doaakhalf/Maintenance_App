<?php

namespace App\Providers;

use App\Models\CalibrationPerform;
use App\Models\CalibrationRequest;
use App\Models\MaintenancePerform;
use App\Models\MaintenanceRequest;
use App\Policies\CalibrationPerformPolicy;
use App\Policies\CalibrationRequestPolicy;
use App\Policies\MaintenancePerformPolicy;
use App\Policies\MaintenanceRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        MaintenanceRequest::class => MaintenanceRequestPolicy::class,
        MaintenancePerform::class => MaintenancePerformPolicy::class,
        CalibrationRequest::class => CalibrationRequestPolicy::class,
        CalibrationPerform::class => CalibrationPerformPolicy::class,

       

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        // Define a Gate for checking roles

        Gate::define('Admin', function () {
            if (Auth::user()->hasRole('Admin')) {
                return true;
            }
            return false;
        });
        Gate::define('Manager', function () {
            if (Auth::user()->hasRole('Manager')) {
                return true;
            }
            return false;
        });
        Gate::define('Technician', function () {
            if (Auth::user()->hasRole('Technician')) {
                return true;
            }
            return false;
        });
        
        Gate::define('RequestMaker', function () {
            if (Auth::user()->hasRole('RequestMaker')) {
                return true;
            }
            return false;
        });
        Gate::define('Admin-Manager', function () {
            if (Auth::user()->hasRole('Admin')|| Auth::user()->hasRole('Manager')) {
                return true;
            }
            return false;
        });
        Gate::define('Admin-Manager-Technician', function () {
            if (Auth::user()->hasRole('Admin')|| Auth::user()->hasRole('Manager')|| Auth::user()->hasRole('Technician')) {
                return true;
            }
            return false;
        });
    }
}
