<?php

namespace Dmyers\Phoney;

class PhoneyMessage
{
    /**
     * The subject of the message.
     *
     * @var string
     */
    public $subject;

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $body;

    /**
     * @param  string  $subject
     * @param  string  $body
     * @return static
     */
    public static function create($subject = '', $body = '')
    {
        return new static($subject, $body);
    }

    /**
     * @param string $subject
     * @param string $body
     */
    public function __construct($subject = '', $body = '')
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Set the subject of the message.
     *
     * @param  string  $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the text content of the message.
     *
     * @param  string  $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }
}
