<?php

namespace App\Events;

use App\Models\MaintenanceRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignBatchRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $maintenanceRequestsIds;
    public $lastMaintenanceRequest;

  


    public function __construct(array $maintenanceRequestsIds,MaintenanceRequest $lastMaintenanceRequest)
    {
        //
        $this->maintenanceRequestsIds=$maintenanceRequestsIds;
        $this->lastMaintenanceRequest=$lastMaintenanceRequest;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('assign-batch-maintenance-requests.' . $this->lastMaintenanceRequest->signed_to_id);

    }
    public function broadcastWith()
    {
        return [
            'maintenance_request_number' => count($this->maintenanceRequestsIds),
            'last_maintenance_request_id' => $this->lastMaintenanceRequest->id,
            'name' => $this->lastMaintenanceRequest->name .'+ ('. count($this->maintenanceRequestsIds) .' Equipment)' ,
            'signed_to_id' => $this->lastMaintenanceRequest->signed_to_id,
            'requester_id' => $this->lastMaintenanceRequest->requester_id,
            'url'=>route('admin.requests.batch.list',$this->lastMaintenanceRequest->batch_id),


        ];
    }
  
  
    public function broadcastAs()
    {
        return 'AssignBatchRequest';
    }
  
}
