<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment ; 

    public function __construct($comment)
    {
        $this->comment = $comment ; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database' , 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'comment'     =>  $this->comment->comment , 
            'post_title'  =>  $this->comment->post->title , 
            'post_slug'   =>  $this->comment->post->slug , 
            'user_name'   =>  $this->comment->user->name , 
        ] ; 
    }


    public function toBroadcast($notifiable): array
    {
        return [
            'comment'     =>  $this->comment->comment , 
            'post_title'  =>  $this->comment->post->title , 
            'post_slug'   =>  $this->comment->post->slug , 
            'user_name'   =>  $this->comment->user->name , 
        ] ; 
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

    public function databaseType(object $notifiable):string
    {
        return 'NotifyUserForNewComment' ;
    }

    public function broadcastType():string
    {
        return 'NotifyUserForNewComment' ;
    }
}
