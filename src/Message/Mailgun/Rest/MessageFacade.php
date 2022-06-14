<?php

namespace G4\Mailer\Message\Mailgun\Rest;

class MessageFacade
{
    /**
     * @param \G4\Mailer\Message $message
     * @param array $options
     * @return Message
     */
    public static function convert(\G4\Mailer\Message $message, array $options)
    {
        $body = [
            'from' => $message->getFrom(),
            'to' => is_array($message->getTo()) ? $message->getTo()[0] : $message->getTo(),
            'subject' => $message->getSubject(),
            'text' => $message->getTextBody(),
            'html' => $message->getHtmlBody(),
        ];

        $url = sprintf($options['params']['url'], $options['params']['domain']);
        $token = $options['params']['token'];

        return new Message($body, $url, $token);
    }
}
