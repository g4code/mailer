<?php

namespace G4\Mailer\Transport\Smtp;

use G4\Mailer\Message\ZendMessageFacade;
use G4\Mailer\Transport\TransportInterface;

class Smtp implements TransportInterface
{
    private $options;

    public function __construct($options)
    {
        $this->setOptions($options);
    }

    public function send(\G4\Mailer\Message $message)
    {
        $options = new \Zend\Mail\Transport\SmtpOptions($this->options);

        // todo adapter will instantiate transport other than smtp
        $transport = new \Zend\Mail\Transport\Smtp($options);

        $transport->send(ZendMessageFacade::convert($message));
    }

    private function setOptions($options)
    {
        if (!isset($options['params']['host'])) {
            throw new \InvalidArgumentException('host not defined');
        }
        if (!isset($options['params']['port'])) {
            throw new \InvalidArgumentException('port not defined');
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