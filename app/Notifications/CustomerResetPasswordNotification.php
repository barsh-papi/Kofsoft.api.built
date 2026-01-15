<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerResetPasswordNotification extends Notification
{


    /**
     * Create a new notification instance.
     */
    public $token;
    public $restaurantName;

    public function __construct($token, $restaurantName)
    {
        $this->token = $token;
        $this->restaurantName = $restaurantName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */


    public function toMail($notifiable)
    {
        $frontendUrl = config('app.frontend_url') ?? 'http://localhost:5173';

        $url = "{$frontendUrl}/r/{$this->restaurantName}/login/resetpassword/{$this->token}?email=" . urlencode($notifiable->getEmailForPasswordReset());


        return (new MailMessage)
            ->subject("Reset Your Password - {$this->restaurantName} Restaurants")
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, please ignore this email.')
            ->salutation('Thank you, The ' . $this->restaurantName . ' Team');
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
