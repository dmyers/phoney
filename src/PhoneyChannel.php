<?php

namespace Dmyers\Phoney;

use Exception;
use Illuminate\Notifications\Notification;

class PhoneyChannel
{
    /**
     * @var \Dmyers\Phoney\Phoney
     */
    protected $phoney;

    /**
     * @param \Dmyers\Phoney\Phoney $phoney
     */
    public function __construct(Phoney $phoney)
    {
        $this->phoney = $phoney;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     * @throws \Exception
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$data = $notifiable->routeNotificationFor('phoney')) {
            return;
        }

        $phoneNumber = array_get($data, 'phone');
        $carrier = array_get($data, 'carrier');
        $country = array_get($data, 'country');
        $message = $notification->toPhoney($notifiable);

        return $this->phoney->sendMessage($phoneNumber, $message->body, $carrier, $country);
    }
}
