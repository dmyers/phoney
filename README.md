# Phoney

Free SMS messaging for Laravel that uses [SMS gateways](https://en.wikipedia.org/wiki/SMS_gateway) to send messages.

### Features

* Laravel Notification channel support

### Install

```sh
$ composer require dmyers/phoney
```

#### Add the route to your notifiable model

```php
/**
 * Route notifications for the Phoney channel.
 *
 * @param  \Illuminate\Notifications\Notification  $notification
 * @return array
 */
public function routeNotificationForPhoney($notification)
{
    return [
        'phone'   => '15551234567',
        'carrier' => 't-mobile',
        'country' => 'United States',
    ];
}
```

#### Add the channel to your notification

```php
use Dmyers\Phoney\PhoneyChannel;
use Dmyers\Phoney\PhoneyMessage;

/**
 * Get the notification's delivery channels.
 *
 * @param  mixed  $notifiable
 * @return array
 */
public function via($notifiable)
{
    return [
        PhoneyChannel::class,
    ];
}

/**
 * Get the phoney representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return \Dmyers\Phoney\PhoneyMessage
 */
public function toPhoney($notifiable)
{
    $subject = 'SMS';
    $body = 'Hello World!';
    return PhoneyMessage::create($subject, $body);
}
```

### Usage

#### Sending messages directly

```php
use Dmyers\Phoney\Phoney;

$phoney = app(Phoney::class);
$phoney->sendMessage('15551234567', 'SMS', 'Hello World!', 't-mobile', 'United States');
```

#### Get a list of supported phone carriers

```php
use Dmyers\Phoney\Phoney;

$phoney = app(Phoney::class);
$phoney->carriers('United States');
```
