<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EquipmentPPMDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $equipment_numbers;
    public function __construct(int $equipment_numbers)
    {
        //
        $this->equipment_numbers=$equipment_numbers;

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
            'name' => 'Equipment PPM Due Maintenance ['.$this->equipment_numbers .'Equipment ]',
            'title' => 'Equipment PPM Due Maintenance ['.$this->equipment_numbers .'Equipment ]',
            'url'=>route('admin.equipment.ppm'),

        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            
            'name' => 'Equipment PPM Due Maintenance ['.$this->equipment_numbers .'Equipment ]',
            'title' => 'Equipment PPM Due Maintenance ['.$this->equipment_numbers .'Equipment ]',
            'url'=>route('admin.equipment.ppm'),

        ]);
    }
}
