<?php

namespace G4\Mailer\Message\MailerQ\Rest;

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
            'envelope'  => $message->getFrom(),
            'recipient' => is_array($message->getTo())? $message->getTo()[0] : $message->getTo(),
            'tags'    => self::getTags($message->getHeaders()),
            'mime'      => [
                'from'    => $message->getFrom(),
                'to'      => $message->getTo(),
                'subject' => $message->getSubject(),
                'text'    => $message->getTextBody(),
                'cc'      => $message->getCc(),
                'bcc'     => $message->getBcc(),
                'replyTo' => $message->getReplyTo(),
                'content' => [
                    'blocks' => [
                        [
                            'type'    => 'html',
                            'content' => $message->getHtmlBody(),
                        ],
                    ]
                ]
            ]
        ];

        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($body)),
            "Authorization: Bearer ". $options['params']['token'],
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