<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceRequestStatusChangedNotify extends Notification implements ShouldQueue
{
    use Queueable;
    protected $maintenanceRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MaintenanceRequest $maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'maintenance_request_id' => $this->maintenanceRequest->id,
            'name' => $this->maintenanceRequest->name,
            'request_date' => $this->maintenanceRequest->request_date,
            'equipment_id' => $this->maintenanceRequest->equipment_id,
            'title' =>'Status Of Maintenance Request Number '. $this->maintenanceRequest->id. 'is changed to '.$this->maintenanceRequest->status,


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'maintenance_request_id' => $this->maintenanceRequest->id,
            'name' => $this->maintenanceRequest->name,
            'request_date' => $this->maintenanceRequest->request_date,
            'equipment_id' => $this->maintenanceRequest->equipment_id,
            'title' =>'Status Of Maintenance Request Number '. $this->maintenanceRequest->id. 'is changed to '.$this->maintenanceRequest->status,



        ]);
    }
}
