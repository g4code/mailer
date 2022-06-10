<?php

namespace G4\Mailer\Transport\Mailgun\Rest;

use G4\Mailer\Client\MailgunCurlHttpClient;
use G4\Mailer\Exception\MailgunMailNotSentException;
use G4\Mailer\Message\Mailgun\Rest\MessageFacade;
use G4\Mailer\Transport\TransportInterface;

class Mailgun implements TransportInterface
{
    private $options;

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function send(\G4\Mailer\Message $message)
    {
        try {
            $mailgunMessage = MessageFacade::convert($message, $this->options);

            (new MailgunCurlHttpClient())->post(
                $mailgunMessage->getBody(),
                $mailgunMessage->getUrl(),
                $mailgunMessage->getToken()
            );
        } catch (\Exception $exception) {
            if ($exception->getMessage() !== sprintf('Empty response from %s', $this->options['url'])) {
                throw new MailgunMailNotSentException(sprintf('Email not sent. Reason: %s', $exception->getMessage()), $exception->getCode());
            }
        }
    }

    private function setOptions($options)
    {
        if (!isset($options['url'])) {
            throw new \InvalidArgumentException('url not defined');
        }

        if (!isset($options['token'])) {
            throw new \InvalidArgumentException('token not defined');
        }

        $this->options = $options;
    }
}