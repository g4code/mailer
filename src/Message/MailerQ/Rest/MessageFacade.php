<?php

namespace G4\Mailer\Message\MailerQ\Rest;

class MessageFacade
{
    const HEADER_MAILERQ_LOGID = 'x-nd-mailerlogid';
    const HEADER_LIST_UNSUBSCRIBE = 'List-Unsubscribe';

    const BCC = 'bcc';
    const CC = 'cc';
    const CONTENT = 'content';
    const ENVELOPE = 'envelope';
    const FEEDBACK_ID = 'Feedback-ID';
    const FROM = 'from';
    const HEADERS = 'headers';
    const INLINECSS = 'inlinecss';
    const MIME = 'mime';
    const PARAMS = 'params';
    const RECIPIENT = 'recipient';
    const REPLY_TO = 'replyTo';
    const SMARTHOST = 'smarthost';
    const SUBJECT = 'subject';
    const TAGS = 'tags';
    const TEXT = 'text';
    const TO = 'to';
    const TOKEN = 'token';
    const URL = 'url';
    const X_NZ_TAGS = 'X-NZ-Tags';

    /**
     * @param \G4\Mailer\Message $message
     * @param array $options
     * @return Message
     */
    public static function convert(\G4\Mailer\Message $message, array $options)
    {
        $body = [
            self::ENVELOPE  => self::getEnvelope($message->getFrom()),
            self::RECIPIENT => is_array($message->getTo())? $message->getTo()[0] : $message->getTo(),
            self::TAGS      => self::getTags($message->getHeaders()),
            self::INLINECSS => true,
            self::MIME      => [
                self::FROM     => $message->getFrom(),
                self::TO       => $message->getTo(),
                self::SUBJECT  => $message->getSubject(),
                self::TEXT     => $message->getTextBody(),
                self::CC       => $message->getCc(),
                self::BCC      => $message->getBcc(),
                self::REPLY_TO => $message->getReplyTo(),
                self::CONTENT  => $message->getHtmlBody(),
            ]
        ];

        if (isset($options[self::PARAMS][self::SMARTHOST])) {
            $body[self::MIME][self::SMARTHOST] = $options[self::PARAMS][self::SMARTHOST];
        }

        if ($message->getLogId()) {
            $body[self::MIME][self::HEADERS][self::HEADER_MAILERQ_LOGID] = $message->getLogId();
        }

        if ($message->getListUnsubscribe()) {
            $body[self::MIME][self::HEADERS][self::HEADER_LIST_UNSUBSCRIBE] = $message->getListUnsubscribe();
        }

        $messageHeaders = $message->getHeaders();

        if (array_key_exists(self::FEEDBACK_ID, $messageHeaders)) {
            $body[self::MIME][self::HEADERS][self::FEEDBACK_ID] = $messageHeaders[self::FEEDBACK_ID];
        }

        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($body)),
            'Authorization: Bearer ' . $options[self::PARAMS][self::TOKEN],
        ];

        $url = $options[self::PARAMS][self::URL];

        return new Message($body, $headers, $url);
    }

    private static function getEnvelope($string)
    {
        preg_match("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
        return $matches ? $matches[0] : htmlspecialchars($string);
    }

    private static function getTags($headers)
    {
        try {
            return explode(',', $headers[self::X_NZ_TAGS]);
        }catch (\Exception $exception) {
            throw new \RuntimeException(sprintf('Tags are not resolved. Reason: %s', $exception->getMessage()));
        }
    }
}