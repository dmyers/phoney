<?php

namespace Dmyers\Phoney;

class Phoney
{
    protected $data;

    public function __construct()
    {
        $this->loadFromFile();
    }

    public function loadFromFile()
    {
        $body = file_get_contents(__DIR__.'/../resources/sms.json');
        $data = json_decode($body, true);

        $this->data = $data;
    }

    public function carriers()
    {
        return $this->data;
    }

    public function gateways()
    {
        return $this->data;
    }
}
