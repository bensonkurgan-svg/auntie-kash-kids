<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public User $user) {}
    public function build()
    {
        return $this->subject('Welcome to Auntie Kash Kids Academy! 🌈')
            ->view('emails.welcome');
    }
}
