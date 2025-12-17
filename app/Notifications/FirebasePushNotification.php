<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;


class FirebasePushNotification extends Notification
{
    use Queueable;
    protected $title;
    protected $body;
    protected $token; // The FCM device token
    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body, $token)
    {
        $this->title = $title;
        $this->body = $body;
        $this->token = $token;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['firebase'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }
    public function toFirebase($notifiable)
    {
        return CloudMessage::withTarget('token', $this->token)
            ->withNotification(FirebaseNotification::create($this->title, $this->body))
            ->withData(['key' => 'value']); // Optional custom data
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
