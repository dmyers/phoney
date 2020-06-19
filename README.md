# Phoney

Free SMS messaging for Laravel that uses [SMS gateways](https://en.wikipedia.org/wiki/SMS_gateway) to send messages.

### Features

* Laravel Notification channel support

### Install

```
$ composer require dmyers/phoney
```

### Usage

```php
$phoney = app(Phoney::class);
$phoneNumber = '15551234567';
$carrier = 't-mobile';
$country = 'United States';
$subject = 'SMS';
$body = 'Your message body.';
$phoney->sendMessage($phoneNumber, $subject, $body, $carrier, $country);
```
