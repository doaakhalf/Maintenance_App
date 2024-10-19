<?php

namespace App\Notifications;

use App\Models\CalibrationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CalibrationRequestStatusChangedNotify extends Notification implements ShouldQueue
{
    use Queueable;
    protected $calibrationRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CalibrationRequest $calibrationRequest)
    {
        $this->calibrationRequest = $calibrationRequest;
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
            'calibration_request_id' => $this->calibrationRequest->id,
            'name' => 'Status Changed of Request',
            'request_date' => $this->calibrationRequest->request_date,
            'equipment_id' => $this->calibrationRequest->equipment_id,
            'title' =>'Status Of Calibration Request Number '. $this->calibrationRequest->id. 'is changed to '.$this->calibrationRequest->status,
            'url'=>route('admin.calibration-request.show',$this->calibrationRequest->id)


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'calibration_request_id' => $this->calibrationRequest->id,
            'name' => 'Status Changed of Request',
            'request_date' => $this->calibrationRequest->request_date,
            'equipment_id' => $this->calibrationRequest->equipment_id,
            'title' => $this->calibrationRequest->name?$this->calibrationRequest->name:'Status Changed of Request',
            'url'=>route('admin.calibration-request.show',$this->calibrationRequest->id)


        ]);
    }
}

