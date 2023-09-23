<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsAppMessage;
use App\Channels\WhatsAppChannel;
use App\Services\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $content;
    public function __construct($content)
    {
       $this->content = $content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }


    public function toWhatsApp($notifiable)
  {
    // $orderUrl = url("/orders/{$this->order->id}");
    // $deliveryDate = $this->order->created_at->addDays(4)->toFormattedDateString();


    return (new WhatsAppMessage)
    ->content($this->content);
        // ->content("Your {$company} order of {$this->order->name} has shipped and should be delivered on {$deliveryDate}. Details: {$orderUrl}");
  }
}
