<?php

declare(strict_types=1);

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
        $data = $notifiable->routeNotificationFor('phoney');
        if (empty($data)) {
            return;
        }

        $phoneNumber = array_get($data, 'phone');
        if (empty($phoneNumber)) {
            return;
        }

        $carrier = array_get($data, 'carrier');
        $country = array_get($data, 'country');
        $message = $notification->toPhoney($notifiable);

        return $this->phoney->sendMessage($phoneNumber, $message->subject, $message->body, $carrier, $country);
    }
}
