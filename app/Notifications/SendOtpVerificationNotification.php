<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpVerificationNotification extends Notification
{
    use Queueable;

    public $otp ; 
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
         $this->otp = new Otp() ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp2 = $this->otp->generate($notifiable->email , 'numeric' , 6 , 10) ; 
        return (new MailMessage)
            ->greeting('Email Verification.') 
            ->line('Otp Code: ' . $otp2->token)
            ->line('Best Regrads , News Portal Wesite Team!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
