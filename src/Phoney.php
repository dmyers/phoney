<?php

namespace Dmyers\Phoney;

use Illuminate\Support\Collection;

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
        return $this->carriers()->pluck('carrier')->sort()->values();
    }

    /**
     * Get the gateways for a carrier.
     *
     * @param  string|null  $country
     * @return Collection
     */
    public function gateways(string $carrier, string $country)
    {
        $carriers = $this->carriers($country, null, $carrier);
        $carrier = $carriers->first();

        return [
            'sms' => array_get($carrier, 'email-to-sms'),
            'mms' => array_get($carrier, 'email-to-mms'),
        ];
    }

    /**
     * Get the text gateway for a carrier.
     *
     * @param  string|null  $country
     * @return Collection
     */
    public function gateway(string $carrier, string $country)
    {
        $gateways = $this->gateways($carrier, $country);
        return array_get($gateways, 'sms');
    }
}
