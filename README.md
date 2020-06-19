# Phoney

Free SMS messaging for Laravel that uses [SMS gateways](https://en.wikipedia.org/wiki/SMS_gateway) to send messages.

### Features

* Laravel Notification channel support

### Install

```
$ composer require dmyers/phoney
```

#### Add the channel to your notification

```php
use Dmyers\Phoney\PhoneyChannel;

/**
 * Get the phoney representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return \Dmyers\Phoney\PhoneyMessage
 */
public function toPhoney($notifiable)
{
    $subject = 'SMS';
    $message = 'Your message body';

    return PhoneyMessage::create($subject, $message);
}

/**
 * Get the notification's delivery channels.
 *
 * @param  mixed  $notifiable
 * @return array
 */
public function via($notifiable)
{
    return [
        PhoneyChannel::class;
    ];
}
```

### Usage

#### Sending messages directly:

```php
use Dmyers\Phoney\Phoney;

$phoney = app(Phoney::class);
$phoneNumber = '15551234567';
$carrier = 't-mobile';
$country = 'United States';
$subject = 'SMS';
$body = 'Your message body.';
$phoney->sendMessage($phoneNumber, $subject, $body, $carrier, $country);
```
