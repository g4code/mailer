<?php

namespace  G4\Mailer\Exception;

class MailerQMailException extends EmailNotSent
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}