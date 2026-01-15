<?php
namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $frontendUrl = config('app.frontend_url') ?? 'http://localhost:5173';

        $url = "{$frontendUrl}/reset-password/{$this->token}?email=" . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Reset Your Password - Kofsoft Restaurants')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We received a request to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, please ignore this email.')
            ->salutation('Thank you, The Kofsoft Team');
    }
}
