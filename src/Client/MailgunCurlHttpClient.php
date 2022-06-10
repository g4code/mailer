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
     * @param array $headers
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

        if ($error) {
            throw new \RuntimeException(sprintf("cURL Error #: %s\n", $error));
        }

        $response = json_decode($result, true);

        if (!$response) {
            throw new \RuntimeException(sprintf('Empty response from %s', $url));
        }

        return $response;
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