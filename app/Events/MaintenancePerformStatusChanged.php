<?php

namespace App\Events;

use App\Models\MaintenancePerform;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenancePerformStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $maintenancePerform;
    public $anotherId;
    public function __construct(MaintenancePerform $maintenancePerform,$anotherId = null)
    {
        $this->maintenancePerform = $maintenancePerform;
        $this->anotherId = $anotherId;

        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
    public function broadcastOn()
    {
        // // return new PrivateChannel('channel-name');
        // return new Channel('maintenance-perform-change-status.' . $this->maintenancePerform->technician_id);
         // Define the channels to broadcast to
         $channels = [
            new Channel('maintenance-perform-change-status.' . $this->maintenancePerform->technician_id),
        ];

        // Add the optional channel if $anotherId is provided
        if ($this->anotherId !== null) {
            $channels[] = new Channel('maintenance-perform-change-status.' . $this->anotherId);
        }

        return $channels;

    }
    public function broadcastWith()
    {
        return [
            'maintenance_perform_id' => $this->maintenancePerform->id,
            'id' => $this->maintenancePerform->id,

            'maintenance_request_id' => $this->maintenancePerform->maintenance_request_id,
            'name' =>'Status Changed of Perform',
            'perform_date' => $this->maintenancePerform->perform_date,
            'status' => $this->maintenancePerform->status,
            'technician_id' => $this->maintenancePerform->technician_id,
            'requester_id' => $this->maintenancePerform->requester_id,
            'url'=>route('admin.maintenance-perform.show',$this->maintenancePerform->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'MaintenancePerformStatusChanged';
    }
}
