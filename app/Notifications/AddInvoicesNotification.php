<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddInvoicesNotification extends Notification
{
    use Queueable;


    private $invoices_id;



    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoices_id)
    {
        $this->invoices_id = $invoices_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return [
            'id' => $this->invoices_id,
            'title' => 'تم اضافة فاتوره جديده بواسطة',
            'user' => Auth::user()->name, 
        ];
    }
}
