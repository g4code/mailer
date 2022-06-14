<?php

namespace G4\Mailer\Client;

class MailgunCurlHttpClient
{
    const API = 'api:';

    /**
     * @var resource
     */
    private $curl;

    /**
     * Send POST curl http request to provided url with provided params and headers
     *
     * @param array $params
     * @param string $url
     * @param string $token
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    public function post(array $params, $url, $token)
    {
        $this->init();

        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_USERPWD => self::API . $token,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HEADER => false
        ]);

        $result = $this->execute();
        $error = $this->error();
        $this->close();

        if (!empty($error) || !strpos($result, 'Queued.')) {
            throw new \RuntimeException(sprintf("cURL Error #: %s\n", $result));
        }

        try {
            return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Empty response from %s', $url));
        }
    }

    /**
     * @return resource
     */
    private function init()
    {
        $this->curl = curl_init();
    }

    /**
     * @return void
     */
    private function close()
    {
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    /**
     * @return bool|string
     */
    private function execute()
    {
        return curl_exec($this->curl);
    }

    /**
     * @return string
     */
    private function error()
    {
        return curl_error($this->curl);
    }
}
