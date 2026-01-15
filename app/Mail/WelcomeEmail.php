<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Kofsoft!')
            ->markdown('emails.welcome')
            ->with([
                'name' => $this->user->firstname . ' ' . $this->user->lastname,
                'plan' => ucfirst($this->user->plan ?? 'free'),
                'trialEnds' => optional( $this->user->trial_ends_at)->format('F j, Y') ?? "N/A",
            ]);
    }
}
