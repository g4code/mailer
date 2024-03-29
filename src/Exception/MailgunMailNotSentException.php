<?php

namespace  G4\Mailer\Exception;

class MailgunMailNotSentException extends EmailNotSentException
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}