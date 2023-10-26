<?php

namespace G4\Mailer\Transport\Smtp;

use G4\Mailer\Exception\SmtpEmailNotSentException;
use G4\Mailer\Message;
use G4\Mailer\Message\LaminasMessageFacade;
use G4\Mailer\Transport\TransportInterface;
use Laminas\Mail\Transport\SmtpOptions;

class Smtp implements TransportInterface
{
    private $options;

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function send(Message $message)
    {
        $options = new SmtpOptions($this->options);

        // todo adapter will instantiate transport other than smtp
        $transport = new \Laminas\Mail\Transport\Smtp($options);

        try {
            $transport->send(LaminasMessageFacade::convert($message));
        } catch (\Laminas\Mail\Exception\RuntimeException $e) {
            throw new SmtpEmailNotSentException($e->getMessage(), $e->getCode());
        }
    }

    private function setOptions($options)
    {
        if (!isset($options['params']['host'])) {
            throw new \InvalidArgumentException('host not defined');
        }
        if (!isset($options['params']['port'])) {
            throw new \InvalidArgumentException('port not defined');
        }
        if (!isset($options['params']['connection_class'])) {
            throw new \InvalidArgumentException('connection_class not defined');
        }
        if (!isset($options['params']['connection_config']['ssl'])) {
            throw new \InvalidArgumentException('ssl not defined');
        }
        if (!isset($options['params']['connection_config']['username'])) {
            throw new \InvalidArgumentException('username not defined');
        }
        if (!isset($options['params']['connection_config']['password'])) {
            throw new \InvalidArgumentException('password not defined');
        }

        $this->options = $options['params'];
    }

}
