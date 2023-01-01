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

class MaintenancePerformCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $maintenancePerform;
    public function __construct(MaintenancePerform $maintenancePerform)
    {
        //
        $this->maintenancePerform=$maintenancePerform;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('maintenance-perform.' . $this->maintenancePerform->requester_id);

    }
    public function broadcastWith()
    {
        return [
            'maintenance_perform_id' => $this->maintenancePerform->id,
            'perform_date' => $this->maintenancePerform->perform_date,
            'service_report' => $this->maintenancePerform->service_report,
            'status' => $this->maintenancePerform->status,
            'technician_id' => $this->maintenancePerform->technician_id,
            'requester_id' => $this->maintenancePerform->requester_id,
            'name'=>'New Maintenance Perform',
            'url'=>route('admin.maintenance-perform.show',$this->maintenancePerform->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'MaintenancePerformCreated';
    }
}
