<?php

namespace G4\Mailer\Message\MailerQ\Rest;

class Message
{
    /**
     * @var array
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $url;

    /**
     * Message constructor.
     * 
     * @param array $body
     * @param array $headers
     * @param string $url
     */
    public function __construct(array $body, array $headers, $url)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}