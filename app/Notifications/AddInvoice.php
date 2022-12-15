<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\invoice;
use Illuminate\Support\Facades\Auth;

class AddInvoice extends Notification
{
    use Queueable;


    public function __construct(invoice $invoice)
    {
        $this->invoice = $invoice;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            // 'data'=>$this->details['body']
            'id'=>$this->invoice->id,
            'title'=>'New Invoice Added By',
            'user'=>Auth::user()->name
    
    ];
    }


}
