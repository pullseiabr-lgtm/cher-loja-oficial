<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;
    public mixed $message;
    public $body;
    public $appName;

    public function __construct($title, $message)
    {
        $this->title   = $title;
        $this->message = $message;
    }

    public function build()
    {
        $template   = EmailTemplate::where('key', 'subscriber')->first();
        $subject    = $template?->subject ?? $this->title;
        $this->body = $template
            ? $template->render(['title' => $this->title, 'message' => $this->message])
            : "<h2>{$this->title}</h2><p>{$this->message}</p>";
        $this->appName = config('app.name');

        return $this->subject($subject)->markdown('emails.generic');
    }
}
