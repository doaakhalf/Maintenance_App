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

class CalibrationPerformCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $calibrationPerform;
    public function __construct(CalibrationPerform $calibrationPerform)
    {
        //
        $this->calibrationPerform=$calibrationPerform;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('calibration-perform.' . $this->calibrationPerform->requester_id);

    }
    public function broadcastWith()
    {
        return [
            'calibration_perform_id' => $this->calibrationPerform->id,
            'id' => $this->calibrationPerform->id,
            'perform_date' => $this->calibrationPerform->perform_date,
            'service_report' => $this->calibrationPerform->service_report,
            'status' => $this->calibrationPerform->status,
            'technician_id' => $this->calibrationPerform->technician_id,
            'requester_id' => $this->calibrationPerform->requester_id,
            'name'=>'New Calibration Perform',
            'url'=>route('admin.calibration-perform.show',$this->calibrationPerform->id)

        ];
    }
  
  
    public function broadcastAs()
    {
        return 'CalibrationPerformCreated';
    }
}
