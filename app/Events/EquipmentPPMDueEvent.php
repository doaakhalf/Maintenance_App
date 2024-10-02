<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EquipmentPPMDueEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $equipment_numbers;

    public function __construct(User $user,int $equipment_numbers)
    {
        //
        $this->user=$user;
        $this->equipment_numbers=$equipment_numbers;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        
        return new Channel('notify-ppm-equipment.' . $this->user->id);

    }
    public function broadcastWith()
    {
        return [
            'name' => 'Equipment PPM Due Maintenance ['.$this->equipment_numbers .'Equipment ]',
            'title' => 'Equipment PPM Due Maintenance ['.$this->equipment_numbers .'Equipment ]',
            'url'=>route('admin.equipment.ppm'),

        ];
    }
    public function broadcastAs()
    {
        return 'EquipmentPPMDueEvent';
    }
}
