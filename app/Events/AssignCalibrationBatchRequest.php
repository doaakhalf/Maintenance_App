<?php

namespace App\Events;

use App\Models\CalibrationRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignCalibrationBatchRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $calibrationRequestsIds;
    public $lastCalibrationRequest;

  


    public function __construct(array $calibrationRequestsIds,CalibrationRequest $lastCalibrationRequest)
    {
        //
        $this->calibrationRequestsIds=$calibrationRequestsIds;
        $this->lastCalibrationRequest=$lastCalibrationRequest;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('assign-batch-calibration-requests.' . $this->lastCalibrationRequest->signed_to_id);

    }
    public function broadcastWith()
    {
        return [
            'calibration_request_number' => count($this->calibrationRequestsIds),
            'last_calibration_request_id' => $this->lastCalibrationRequest->id,
            'name' => $this->lastCalibrationRequest->name .'+ ('. count($this->calibrationRequestsIds) .' Equipment)' ,
            'signed_to_id' => $this->lastCalibrationRequest->signed_to_id,
            'requester_id' => $this->lastCalibrationRequest->requester_id,
            'url'=>route('admin.calibration_request.batch.list',$this->lastCalibrationRequest->batch_id),


        ];
    }
  
  
    public function broadcastAs()
    {
        return 'AssignCalibrationBatchRequest';
    }
  
}
