<?php

namespace Dmyers\Phoney;

use Exception;
use Illuminate\Notifications\Notification;

class PhoneyChannel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @throws \Exception
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) {
//            throw Exception::serviceRespondedWithAnError($response);
//        }
    }
}
