<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddInvoices extends Notification
{
    use Queueable;

    public $invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->$invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $url = "http://127.0.0.1/invoices/public/invoicesDetails/$this->invoice";

        return (new MailMessage)
            // ->to('wd2831998@gmial.com')
            ->subject('تم اضافة فاتورة جديده')
            ->line('The introduction to the notification.')
            ->action(' عرض الفاتورة', $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
