<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pin;
    public $body;
    public $appName;

    public function __construct($pin)
    {
        $this->pin = $pin;
    }

    public function build()
    {
        $template   = EmailTemplate::where('key', 'verify_email')->first();
        $subject    = $template?->subject ?? 'Verificação de E-mail';
        $this->body = $template
            ? $template->render(['pin' => $this->pin])
            : "<p>Seu código é: <strong>{$this->pin}</strong></p>";
        $this->appName = config('app.name');

        return $this->subject($subject)->markdown('emails.generic');
    }
}
