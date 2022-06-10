<?php

namespace G4\Mailer\Message\Mailgun\Rest;

class Message
{
    /**
     * @var array
     */
    private $body;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $token;

    /**
     * Message constructor.
     * 
     * @param array $body
     * @param string $url
     * @param string $token
     */
    public function __construct(array $body, $url, $token)
    {
        $this->body = $body;
        $this->url = $url;
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}