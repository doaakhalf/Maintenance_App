<?php

namespace App\Notifications;

use App\Models\MaintenancePerform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenancePerformReply extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $maintenancePerform;

    public function __construct(MaintenancePerform $maintenancePerform)
    {
        //
        $this->maintenancePerform=$maintenancePerform;
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
            'maintenance_request_id' => $this->maintenancePerform->maintenance_request_id,
            'perform_date' => $this->maintenancePerform->perform_date,
            'title' =>'New Maintenance Perform',

        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'maintenance_perform_id' => $this->maintenancePerform->id,
            'maintenance_request_id' => $this->maintenancePerform->maintenance_request_id,
            'perform_date' => $this->maintenancePerform->perform_date,
            'title' =>'New Maintenance Perform',

        ]);
    }
}
