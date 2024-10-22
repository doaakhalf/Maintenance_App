<?php

namespace App\Notifications;

use App\Models\CalibrationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignCalibrationBatchRequestNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $calibrationRequestsIds;
    protected $lastCalibrationRequest;
    public function __construct(array $calibrationRequestsIds,CalibrationRequest $lastCalibrationRequest)
    {
        //
        $this->calibrationRequestsIds=$calibrationRequestsIds;
        $this->lastCalibrationRequest=$lastCalibrationRequest;

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
            'calibration_request_number' => count($this->calibrationRequestsIds),
            'last_calibration_request_id' => $this->lastCalibrationRequest->id,
            'name' => $this->lastCalibrationRequest->name .'+ ('. count($this->calibrationRequestsIds) .' Equipment)' ,
            'title' => $this->lastCalibrationRequest->name .'+ ('. count($this->calibrationRequestsIds) .' Equipment)' ,
            'signed_to_id' => $this->lastCalibrationRequest->signed_to_id,
            'requester_id' => $this->lastCalibrationRequest->requester_id,
            'url'=>route('admin.calibration_request.batch.list',$this->lastCalibrationRequest->batch_id),


        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'calibration_request_number' => count($this->calibrationRequestsIds),
            'last_calibration_request_id' => $this->lastCalibrationRequest->id,
            'name' => $this->lastCalibrationRequest->name .'+ ('. count($this->calibrationRequestsIds) .' Equipment)' ,
            'title' => $this->lastCalibrationRequest->name .'+ ('. count($this->calibrationRequestsIds) .' Equipment)' ,
            'signed_to_id' => $this->lastCalibrationRequest->signed_to_id,
            'requester_id' => $this->lastCalibrationRequest->requester_id,
            'url'=>route('admin.calibration_request.batch.list',$this->lastCalibrationRequest->batch_id),



        ]);
    }
}
