<?php

namespace G4\Mailer;


class Message
{
    /**
     * @var array
     */
    private $to;

    /**
     * @var array
     */
    private $cc;

    /**
     * @var array
     */
    private $bcc;
    
    /**
     * @var array
     */
    private $headers;

    private $from;
    private $replyTo;
    private $subject;
    private $htmlBody;
    private $textBody;

    public function __construct($to, $from, $subject, $htmlBody, $textBody='')
    {
        $this->to[] = $to;
        $this->cc = [];
        $this->bcc = [];
        $this->headers = [];
        $this->from = $from;
        $this->subject = $subject;
        $this->htmlBody = $htmlBody;
        $this->textBody = $textBody ? $textBody : strip_tags($htmlBody);
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }
    
    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * @return bool
     */
    public function hasHeaders()
    {
        return count($this->headers) > 0;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @return string
     */
    public function getTextBody()
    {
        return $this->textBody;
    }


    /**
     * @param $recipient
     * @return $this
     */
    public function addTo($recipient)
    {
        $this->to[] = $recipient;
        return $this;
    }

    /**
     * @param $recipient
     * @return $this
     */
    public function addCc($recipient)
    {
        $this->cc[] = $recipient;
        return $this;
    }

    /**
     * @param $recipient
     * @return $this
     */
    public function addBcc($recipient)
    {
        $this->bcc[] = $recipient;
        return $this;
    }
    
    /**
     * @param $header
     * @return $this
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }
    
    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function setReplyTo($recipient)
    {
        $this->replyTo = $recipient;
        return $this;
    }

}