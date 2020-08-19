<?php

namespace Dmyers\Phoney;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class Phoney
{
    /** @var string */
    protected const CACHE_KEY = 'phoney.db';

    /** @var Collection */
    protected $data;

    public static $mailCallback;

    public function __construct()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->loadFromCache();
    }

    public function loadFromCache($ttl = null)
    {
        if (empty($ttl)) {
            $ttl = now()->addDays(30);
        }

        return Cache::remember(self::CACHE_KEY, $ttl, function () {
            return $this->loadFromFile();
        });
    }

    public function loadFromFile()
    {
        if (!is_null($this->data)) {
            return $this->data;
        }

        $body = file_get_contents(__DIR__.'/../resources/sms.json');
        $data = json_decode($body, true);

        $this->data = Collection::make($data);
    }

    /**
     * Get the list of phone carriers.
     *
     * @param  string|null  $country
     * @param  string|null  $region
     * @param  string|null  $name
     * @param  string|null  $slug
     * @return Collection
     */
    public function carriers(?string $country = null, ?string $region = null, ?string $name = null, ?string $slug = null)
    {
        $data = $this->data->map(function ($item) {
            $item['slug'] = str_slug($item['carrier']);
            return $item;
        });

        if (!empty($country)) {
            $data = $data->filter(function ($item) use ($country) {
                return $item['country'] == $country;
            });
        }
        if (!empty($region)) {
            $data = $data->filter(function ($item) use ($region) {
                return $item['region'] == $region;
            });
        }
        if (!empty($name)) {
            $data = $data->filter(function ($item) use ($name) {
                return $item['carrier'] == $name;
            });
        }
        if (!empty($slug)) {
            $data = $data->filter(function ($item) use ($slug) {
                return $item['slug'] == $slug;
            });
        }

        return $data;
    }

    /**
     * Get the list of carriers.
     *
     * @param  string|null  $country
     * @param  string|null  $region
     * @return Collection
     */
    public function carrierNames(?string $country = null, ?string $region = null)
    {
        return $this->carriers($country, $region)->pluck('carrier', 'slug')->sort();
    }

    /**
     * Get the gateways for a carrier.
     *
     * @param  string  $country
     * @param  string  $carrier
     * @param  string|null  $region
     * @return Collection
     */
    public function gateways(string $carrier, string $country, ?string $region = null)
    {
        $carriers = $this->carriers($country, $region, null, $carrier);
        $carrier = $carriers->first();

        return [
            'sms' => array_get($carrier, 'email-to-sms'),
            'mms' => array_get($carrier, 'email-to-mms'),
        ];
    }

    /**
     * Get the text gateway for a carrier.
     *
     * @param  string  $country
     * @param  string  $carrier
     * @param  string|null  $region
     * @return Collection
     */
    public function gateway(string $carrier, string $country, ?string $region = null)
    {
        $gateways = $this->gateways($carrier, $country, $region);
        $sms = array_get($gateways, 'sms');
        if (!empty($sms)) {
            return $sms;
        }
        $mms = array_get($gateways, 'mms');
        if (!empty($mms)) {
            return $mms;
        }

        return null;
    }

    /**
     * Formats a phone number to be used with
     * the text gateway for a carrier.
     *
     * @param  string  $phoneNumber
     * @param  string  $country
     * @param  string  $carrier
     * @param  string|null  $region
     * @return Collection
     */
    public function formatAddress(string $phoneNumber, string $carrier, string $country, ?string $region = null)
    {
        $gateway = $this->gateway($carrier, $country, $region);
        return str_replace('number', $phoneNumber, $gateway);
    }

    /**
     * Sends a message to a phone number using
     * the gateway for a carrier.
     *
     * @param  string  $phoneNumber
     * @param  string  $subject
     * @param  string  $body
     * @param  string  $country
     * @param  string  $carrier
     * @param  string|null  $region
     * @return mixed
     */
    public function sendMessage(string $phoneNumber, string $subject, string $body, string $carrier, string $country, ?string $region = null)
    {
        $email = $this->formatAddress($phoneNumber, $carrier, $country, $region);
        $callback = static::$mailCallback;

        return Mail::raw($body, function ($msg) use ($phoneNumber, $email, $subject, $carrier, $callback) {
            $msg->to($email)
                ->subject($subject)
                ->priority(3);

            if (!empty($callback)) {
                $callback($msg, $phoneNumber, $carrier);
            }
        });
    }

    /**
     * Register a callback to be called while sending mail.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function buildMailUsing(callable $callback)
    {
        static::$mailCallback = $callback;
    }
}
