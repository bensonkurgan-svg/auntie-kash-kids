<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DiscoveryForm;

class DiscoveryConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public DiscoveryForm $form) {}
    public function build()
    {
        return $this->subject('Your Discovery Call Request for '.$this->form->child_name.' 🌈')
            ->view('emails.discovery-confirmation');
    }
}
