<?php

namespace G4\Mailer;

use G4\Mailer\Transport\Amazon\AmazonSes;
use G4\Mailer\Transport\Smtp\Smtp;
use G4\Mailer\Transport\TransportInterface;

class Mailer implements  MailerInterface
{
    /**
     * @var TransportInterface
     */
    private $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function send(\G4\Mailer\Message $message)
    {
        $this->transport->send($message);
    }

    public static function factory($options)
    {
        if (!isset($options['delivery'])) {
            throw new \InvalidArgumentException('Key "delivery" not specified, should be smtp, amazon_sess, etc...');
        }

        switch ($options['delivery']) {
            case 'smtp';       $transport = new Smtp($options); break;
            case 'amazon_ses'; $transport = new AmazonSes($options); break;
            default:
                throw new \Exception("Mail delivery not defined");
        }

        return $transport;
    }
}