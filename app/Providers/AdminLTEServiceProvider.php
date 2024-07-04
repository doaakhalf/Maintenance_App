<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminLTEServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
       
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
      
        View::composer('*', function ($view) {
            // Pass dynamic data to the views
            $newDepartmentsCount = Department::count(); 
            $view->with('newDepartmentsCount', $newDepartmentsCount);


        });
    }
}
