<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public User $user, public string $plainPassword) {}
    public function build()
    {
        return $this->subject('Your Auntie Kash Kids '.ucfirst(strtolower($this->user->role)).' Account')
            ->view('emails.staff-welcome');
    }
}
