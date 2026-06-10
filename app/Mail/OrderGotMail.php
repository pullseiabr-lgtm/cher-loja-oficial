<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderGotMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $orderId;
    public mixed $message;
    public $body;
    public $appName;

    public function __construct($orderId, $message)
    {
        $this->orderId = $orderId;
        $this->message = $message;
    }

    public function build()
    {
        $template   = EmailTemplate::where('key', 'order_got')->first();
        $subject    = $template?->subject ?? 'Você recebeu um novo pedido';
        $this->body = $template
            ? $template->render(['order_id' => $this->orderId, 'message' => $this->message])
            : "<p>Novo pedido #{$this->orderId}: {$this->message}</p>";
        $this->appName = config('app.name');

        return $this->subject($subject)->markdown('emails.generic');
    }
}
