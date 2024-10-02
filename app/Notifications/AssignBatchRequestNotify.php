<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignBatchRequestNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $maintenanceRequestsIds;
    protected $lastMaintenanceRequest;
    public function __construct(array $maintenanceRequestsIds,MaintenanceRequest $lastMaintenanceRequest)
    {
        //
        $this->maintenanceRequestsIds=$maintenanceRequestsIds;
        $this->lastMaintenanceRequest=$lastMaintenanceRequest;

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
            'maintenance_request_number' => count($this->maintenanceRequestsIds),
            'last_maintenance_request_id' => $this->lastMaintenanceRequest->id,
            'name' => $this->lastMaintenanceRequest->name .'+ ('. count($this->maintenanceRequestsIds) .' Equipment)' ,
            'title' => $this->lastMaintenanceRequest->name .'+ ('. count($this->maintenanceRequestsIds) .' Equipment)' ,
            'signed_to_id' => $this->lastMaintenanceRequest->signed_to_id,
            'requester_id' => $this->lastMaintenanceRequest->requester_id,
            'url'=>route('admin.requests.patch.list',$this->lastMaintenanceRequest->batch_id),


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'maintenance_request_number' => count($this->maintenanceRequestsIds),
            'last_maintenance_request_id' => $this->lastMaintenanceRequest->id,
            'name' => $this->lastMaintenanceRequest->name .'+ ('. count($this->maintenanceRequestsIds) .' Equipment)' ,
            'title' => $this->lastMaintenanceRequest->name .'+ ('. count($this->maintenanceRequestsIds) .' Equipment)' ,
            'signed_to_id' => $this->lastMaintenanceRequest->signed_to_id,
            'requester_id' => $this->lastMaintenanceRequest->requester_id,
            'url'=>route('admin.requests.patch.list',$this->lastMaintenanceRequest->batch_id),



        ]);
    }
}
