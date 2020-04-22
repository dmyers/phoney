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
        if (! $phoneNumber = $notifiable->routeNotificationFor('phoney')) {
            return;
        }

        $message = $notification->toPhoney($notifiable);

        return $this->phoney->send($phoneNumber, [
            'content' => $message->body,
        ]);
    }
}
