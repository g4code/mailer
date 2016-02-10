<?php

namespace G4\Mailer\Transport;

interface TransportInterface
{
    public function send(\G4\Mailer\Message $message);
}