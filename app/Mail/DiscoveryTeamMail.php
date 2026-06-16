<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DiscoveryForm;

class DiscoveryTeamMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public DiscoveryForm $form) {}
    public function build()
    {
        return $this->subject('🌟 New Discovery Call — '.$this->form->child_name.' (Age '.$this->form->child_age.')')
            ->view('emails.discovery-team');
    }
}
