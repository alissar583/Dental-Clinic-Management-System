<?php

namespace App\Services;

use Twilio\Rest\Client;

/**
 * Class TwilioService.
 */
class TwilioService
{
    public function send($message)
    {

        // $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('services.twilio.whatsapp_from');


        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));


        return $twilio->messages->create('whatsapp:' . '+963965237881', [
            "from" => 'whatsapp:' . $from,
            "body" => $message
        ]);
    }
}
