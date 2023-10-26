<?php

namespace G4\Mailer\Message;

class LaminasMessageFacade
{
    const ENCODING  = 'utf-8';
    const TYPE_HTML = 'text/html';
    const TYPE_TEXT = 'text/plain';

    public static function convert(\G4\Mailer\Message $message): \Laminas\Mail\Message
    {
        $htmlPart = new \Laminas\Mime\Part($message->getHtmlBody());
        $htmlPart->charset = self::ENCODING;
        $htmlPart->type = self::TYPE_HTML;
        $htmlPart->encoding = \Laminas\Mime\Mime::ENCODING_QUOTEDPRINTABLE;

        $textPart = new \Laminas\Mime\Part($message->getTextBody());
        $textPart->charset = self::ENCODING;
        $textPart->type = self::TYPE_TEXT;
        $textPart->encoding = \Laminas\Mime\Mime::ENCODING_QUOTEDPRINTABLE;

        $body = new \Laminas\Mime\Message();
        $body->setParts([$textPart, $htmlPart]);

        $laminasMessage = new \Laminas\Mail\Message();
        $laminasMessage
            ->addTo($message->getTo())
            ->addFrom(
                self::getEmailPart($message->getFrom()),
                self::getNamePart($message->getFrom())
            )
            ->setSender(
                self::getEmailPart($message->getFrom()),
                self::getNamePart($message->getFrom())
            )
            ->setSubject($message->getSubject())
            ->setBody($body)
            ->setEncoding(self::ENCODING)
            ->getHeaders()->get('content-type')->setType('multipart/alternative');

        if (count($message->getCc())) {
            $laminasMessage->addCc($message->getCc());
        }
        if (count($message->getBcc())) {
            $laminasMessage->addBcc($message->getBcc());
        }
        if ($message->getReplyTo()) {
            $laminasMessage->setReplyTo(
                self::getEmailPart($message->getReplyTo()),
                self::getNamePart($message->getReplyTo())
            );
        }
        if ($message->hasHeaders()) {
            $laminasMessage->getHeaders()->addHeaders($message->getHeaders());
        }

        return $laminasMessage;
    }

    private static function getEmailPart($from)
    {
        if (preg_match('/(.*) <(.*)>/', $from, $regs)) {
            // match Sender <email@example.com>
            return $regs[2];
        }
        return $from;
    }

    private static function getNamePart($from)
    {
        if (preg_match('/(.*) <(.*)>/', $from, $regs)) {
            // match Sender <email@example.com>
            return trim($regs[1]);
        }
        return null;
    }
}
