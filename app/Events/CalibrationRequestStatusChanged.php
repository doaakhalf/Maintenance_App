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

class CalibrationRequestStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $calibrationRequest;
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
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('calibration-request-change-status.' . $this->calibrationRequest->signed_to_id);

    }
    public function broadcastWith()
    {
        return [
            'calibration_request_id' => $this->calibrationRequest->id,
            'id' => $this->calibrationRequest->id,

            'name' =>'Status Changed of Request',
            'request_date' => $this->calibrationRequest->request_date,
            'type' => $this->calibrationRequest->type,
            'status' => $this->calibrationRequest->status,
            'equipment_id' => $this->calibrationRequest->equipment_id,
            'signed_to_id' => $this->calibrationRequest->signed_to_id,
            'requester_id' => $this->calibrationRequest->requester_id,
            'url'=>route('admin.calibration-request.show',$this->calibrationRequest->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'CalibrationRequestStatusChanged';
    }
}
