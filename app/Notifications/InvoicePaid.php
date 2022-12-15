<?php

namespace App\Notifications;

use App\Models\invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;
    private $invoice_id;

    public function __construct($invoice_id)
    {
        $this->invoice_id = $invoice_id;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        $url = 'http://127.0.0.1:8000/InvoicesDetails/'.$this->invoice_id;

        return (new MailMessage)                 
                    ->subject('Add new invoice')
                    ->line('Add new invoice')
                    ->action('Show invoice', $url)
                    ->line('Thank you to use ERCODE');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
