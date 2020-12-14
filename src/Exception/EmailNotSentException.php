<?php

namespace G4\Mailer\Exception;

class EmailNotSentException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        if (!$message) {
            $message = 'EMAIL_NOT_SENT';
        }
        parent::__construct($message, $code, $previous);
    }
}