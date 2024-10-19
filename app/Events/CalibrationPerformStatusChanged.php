<?php

namespace App\Events;

use App\Models\CalibrationPerform;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CalibrationPerformStatusChanged implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $calibrationPerform;
    public $anotherId;
    public function __construct(CalibrationPerform $calibrationPerform,$anotherId = null)
    {
        $this->calibrationPerform = $calibrationPerform;
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
        // return new Channel('calibration-perform-change-status.' . $this->calibrationPerform->technician_id);
         // Define the channels to broadcast to
         $channels = [
            new Channel('calibration-perform-change-status.' . $this->calibrationPerform->technician_id),
        ];

        // Add the optional channel if $anotherId is provided
        if ($this->anotherId !== null) {
            $channels[] = new Channel('calibration-perform-change-status.' . $this->anotherId);
        }

        return $channels;

    }
    public function broadcastWith()
    {
        return [
            'calibration_perform_id' => $this->calibrationPerform->id,
            'id' => $this->calibrationPerform->id,

            'calibration_request_id' => $this->calibrationPerform->calibration_request_id,
            'name' =>'Status Changed of Perform',
            'perform_date' => $this->calibrationPerform->perform_date,
            'status' => $this->calibrationPerform->status,
            'technician_id' => $this->calibrationPerform->technician_id,
            'requester_id' => $this->calibrationPerform->requester_id,
            'url'=>route('admin.calibration-perform.show',$this->calibrationPerform->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'CalibrationPerformStatusChanged';
    }
}
