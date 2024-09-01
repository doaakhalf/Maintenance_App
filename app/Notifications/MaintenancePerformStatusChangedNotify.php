<?php

namespace App\Notifications;

use App\Models\MaintenancePerform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenancePerformStatusChangedNotify extends Notification implements ShouldQueue
{
    use Queueable;
    protected $maintenancePerform;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MaintenancePerform $maintenancePerform)
    {
        $this->maintenancePerform = $maintenancePerform;
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
            'maintenance_perform_id' => $this->maintenancePerform->id,
            'name' => 'Status Changed of Perform',
            'perform_date' => $this->maintenancePerform->request_date,
            'maintenance_request_id' => $this->maintenancePerform->maintenance_request_id,
            'title' =>'Status Of Maintenance Perform Number '. $this->maintenancePerform->id. 'is changed to '.$this->maintenancePerform->status,
            'url'=>route('admin.maintenance-perform.show',$this->maintenancePerform->id)


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'maintenance_perform_id' => $this->maintenancePerform->id,
            'name' => 'Status Changed of Perform',
            'perform_date' => $this->maintenancePerform->request_date,
            'maintenance_request_id' => $this->maintenancePerform->maintenance_request_id,
            'title' =>'Status Of Maintenance Perform Number '. $this->maintenancePerform->id. 'is changed to '.$this->maintenancePerform->status,
            'url'=>route('admin.maintenance-perform.show',$this->maintenancePerform->id)


        ]);
    }
}
