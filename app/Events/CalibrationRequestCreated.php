<?php

namespace App\Events;

use App\Models\CalibrationRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CalibrationRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $calibrationRequest;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CalibrationRequest $calibrationRequest)
    {
        $this->calibrationRequest = $calibrationRequest;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('calibration-request.' . $this->calibrationRequest->signed_to_id);

        // return new PrivateChannel('calibration-request.' . $this->calibrationRequest->signed_to_id);
    }
    public function broadcastWith()
    {
        return [
            'calibration_request_id' => $this->calibrationRequest->id,
            'id' => $this->calibrationRequest->id,

            'name' => $this->calibrationRequest->name?$this->calibrationRequest->name:'New Maintenance Request',
            'request_date' => $this->calibrationRequest->request_date,
            'type' => $this->calibrationRequest->type,
            'status' => $this->calibrationRequest->status,
            'equipment_id' => $this->calibrationRequest->equipment_id,
            'signed_to_id' => $this->calibrationRequest->signed_to_id,
            'requester_id' => $this->calibrationRequest->requester_id,
            'url'=>route('admin.calibration-perform.create',$this->calibrationRequest->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'CalibrationRequestCreated';
    }
}