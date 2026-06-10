<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public int $orderId;
    public mixed $message;
    public $body;
    public $appName;

    public function __construct($name, $orderId, $message)
    {
        $this->name    = $name;
        $this->orderId = $orderId;
        $this->message = $message;
    }

    public function build()
    {
        $template   = EmailTemplate::where('key', 'order_notification')->first();
        $subject    = $template?->subject ?? 'Atualização do seu pedido';
        $this->body = $template
            ? $template->render(['name' => $this->name, 'order_id' => $this->orderId, 'message' => $this->message])
            : "<p>Olá, {$this->name}! Pedido #{$this->orderId}: {$this->message}</p>";
        $this->appName = config('app.name');

        return $this->subject($subject)->markdown('emails.generic');
    }
}
