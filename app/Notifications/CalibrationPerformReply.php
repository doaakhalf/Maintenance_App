<?php

namespace App\Notifications;

use App\Models\CalibrationPerform;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CalibrationPerformReply extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
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
            'calibration_perform_id' => $this->calibrationPerform->id,
            'calibration_request_id' => $this->calibrationPerform->calibration_request_id,
            'perform_date' => $this->calibrationPerform->perform_date,
            'title' =>'New Calibration Perform',
            'url'=>route('admin.calibration-perform.show',$this->calibrationPerform->id)


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'calibration_perform_id' => $this->calibrationPerform->id,
            'calibration_request_id' => $this->calibrationPerform->calibration_request_id,
            'perform_date' => $this->calibrationPerform->perform_date,
            'title' =>'New Calibration Perform',
            'url'=>route('admin.calibration-perform.show',$this->calibrationPerform->id)


        ]);
    }
}