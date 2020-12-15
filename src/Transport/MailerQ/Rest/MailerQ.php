<?php

namespace G4\Mailer\Transport\MailerQ\Rest;

use G4\Mailer\Client\CurlHttpClient;
use G4\Mailer\Exception\MailerqMailNotSentException;
use G4\Mailer\Transport\TransportInterface;
use G4\Mailer\Message\MailerQ\Rest\MessageFacade;

class MailerQ implements TransportInterface
{
    private $options;

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function send(\G4\Mailer\Message $message)
    {
        try {
            $mailerQMessage = MessageFacade::convert($message, $this->options);

            (new CurlHttpClient())->post($mailerQMessage->getBody(), $mailerQMessage->getHeaders(), $mailerQMessage->getUrl());
        } catch (\Exception $exception) {
            if ($exception->getMessage() !== sprintf('Empty response from %s', $this->options['params']['url'])) {
                throw new MailerqMailNotSentException(sprintf('Email not sent. Reason: %s', $exception->getMessage()), $exception->getCode());
            }
        }
    }

    private function setOptions($options)
    {
        if (!isset($options['params']['url'])) {
            throw new \InvalidArgumentException('url not defined');
        }

        if (!isset($options['params']['token'])) {
            throw new \InvalidArgumentException('token not defined');
        }

        $this->options = $options;
    }
}