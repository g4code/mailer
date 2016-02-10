<?php

namespace G4\Mailer;


interface MailerInterface
{
    public function send(\G4\Mailer\Message $message);
}