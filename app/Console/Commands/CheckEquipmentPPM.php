<?php

namespace App\Console\Commands;

use App\Events\EquipmentPPMDueEvent;
use App\Models\Equipment;
use App\Models\User;
use App\Notifications\EquipmentPPMDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CheckEquipmentPPM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppm:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all equipment need to maintenance according to ppm';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admin_managers = User::whereHas('role', function (Builder $query) {
            $query->where('role_name', 'Admin')
                ->orWhere('role_name', 'Manager');
        })->get();
              // Current date
              $today = Carbon::today();

              // Query to get all equipment that needs maintenance today
              $equipmentDueForMaintenance = Equipment::where(function ($query) use ($today) {
                  $query->where(function ($q) use ($today) {
                      // For 'Month' ppm_unit
                      $q->where('ppm_unit', 'Month')
                          ->whereRaw("DATE(created_at + INTERVAL ppm MONTH) <= ?", [$today]);
                  })->orWhere(function ($q) use ($today) {
                      // For 'Day' ppm_unit
                      $q->where('ppm_unit', 'Day')
                          ->whereRaw("DATE(created_at + INTERVAL ppm DAY) <= ?", [$today]);
                  })->orWhere(function ($q) use ($today) {
                      // For 'Year' ppm_unit
                      $q->where('ppm_unit', 'Year')
                          ->whereRaw("DATE(created_at + INTERVAL ppm YEAR) <= ?", [$today]);
                  });
              })->get();
              // Loop through each user and fire an event for their specific channel
    foreach ($admin_managers as $user) {

        // Fire the event and use a channel specific to the user's ID
        $user->notify(new EquipmentPPMDueNotification(count( $equipmentDueForMaintenance)));
        // Fire event to send realtime notification
        event(new EquipmentPPMDueEvent($user,count( $equipmentDueForMaintenance)));
        // This event can then be broadcasted to a channel like 'notify-ppm-equipment.{user_id}'
    }
        return Command::SUCCESS;
    }
}
