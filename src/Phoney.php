<?php

namespace Dmyers\Phoney;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class Phoney
{
    /** @var Collection */
    protected $data;

    public function __construct()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->loadFromFile();
    }

    public function loadFromCache()
    {
        //
    }

    public function loadFromFile()
    {
        if (!is_null($this->data)) return;

        $body = file_get_contents(__DIR__.'/../resources/sms.json');
        $data = json_decode($body, true);

        $this->data = Collection::make($data);
    }

    /**
     * Get the list of carriers.
     *
     * @param  string|null  $country
     * @param  string|null  $region
     * @param  string|null  $name
     * @return Collection
     */
    public function carriers(?string $country = null, ?string $region = null, ?string $name = null)
    {
        $data = $this->data;

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
        return $this->carriers($country, $region)->pluck('carrier')->sort()->values();
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
        $carriers = $this->carriers($country, $region, $carrier);
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
        if (!empty($sms)) return $sms;
        $mms = array_get($gateways, 'mms');
        if (!empty($mms)) return $mms;
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
     * @param  string  $body
     * @param  string  $country
     * @param  string  $carrier
     * @param  string|null  $region
     * @return mixed
     */
    public function sendMessage(string $phoneNumber, string $body, string $carrier, string $country, ?string $region = null)
    {
        $email = $this->formatAddress($phoneNumber, $carrier, $country, $region);
        $subject = config('app.name');

        return Mail::raw($body, function ($msg) use ($email, $subject) {
            $msg->to($email);
            $msg->subject($subject);
        });
    }
}
