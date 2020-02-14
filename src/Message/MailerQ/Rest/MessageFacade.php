<?php

namespace G4\Mailer\Message\MailerQ\Rest;

class MessageFacade
{
    const HEADER_MAILERQ_LOGID = 'x-nd-mailerlogid';
    const HEADER_LIST_UNSUBSCRIBE = 'List-Unsubscribe';

    /**
     * @param \G4\Mailer\Message $message
     * @param array $options
     * @return Message
     */
    public static function convert(\G4\Mailer\Message $message, array $options)
    {
        $body = [
            'envelope'  => $message->getFrom(),
            'recipient' => is_array($message->getTo())? $message->getTo()[0] : $message->getTo(),
            'tags'    => self::getTags($message->getHeaders()),
            'inlinecss' => true,
            'mime'      => [
                'from'    => $message->getFrom(),
                'to'      => $message->getTo(),
                'subject' => $message->getSubject(),
                'text'    => $message->getTextBody(),
                'cc'      => $message->getCc(),
                'bcc'     => $message->getBcc(),
                'replyTo' => $message->getReplyTo(),
                'content' => $message->getHtmlBody(),
            ]
        ];

        if ($message->getLogId()) {
            $body['mime']['headers'][self::HEADER_MAILERQ_LOGID] = $message->getLogId();
        }

        if ($message->getListUnsubscribe()) {
            $body['mime']['headers'][self::HEADER_LIST_UNSUBSCRIBE] = $message->getListUnsubscribe();
        }

        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($body)),
            'Authorization: Bearer ' . $options['params']['token'],
        ];

        $url = $options['params']['url'];

        return new Message($body, $headers, $url);
    }

    private static function getTags($headers)
    {
        try {
            return explode(",", $headers['X-NZ-Tags']);
        }catch (\Exception $exception) {
            throw new \RuntimeException(sprintf('Tags are not resolved. Reason: %s', $exception->getMessage()));
        }
    }
}