<?php

namespace App\Events;

use App\Models\MaintenanceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceRequestStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $maintenanceRequest;
    public function __construct(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
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
        // return new PrivateChannel('channel-name');
        return new Channel('maintenance-request-change-status.' . $this->maintenanceRequest->signed_to_id);

    }
    public function broadcastWith()
    {
        return [
            'maintenance_request_id' => $this->maintenanceRequest->id,
            'id' => $this->maintenanceRequest->id,

            'name' =>'Status Changed of Request',
            'request_date' => $this->maintenanceRequest->request_date,
            'type' => $this->maintenanceRequest->type,
            'status' => $this->maintenanceRequest->status,
            'equipment_id' => $this->maintenanceRequest->equipment_id,
            'signed_to_id' => $this->maintenanceRequest->signed_to_id,
            'requester_id' => $this->maintenanceRequest->requester_id,
            'url'=>route('admin.maintenance-requests.show',$this->maintenanceRequest->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'MaintenanceRequestStatusChanged';
    }
}
