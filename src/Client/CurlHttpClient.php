<?php

namespace G4\Mailer\Client;

class CurlHttpClient
{
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
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    public function post(array $params, array $headers, $url)
    {
        $this->init();

        curl_setopt_array($this->curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT    => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($params),
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        $result = $this->execute();
        $error = $this->error();

        $this->close();

        if ($error) {
            throw new \RuntimeException(sprintf("cURL Error #: %s\n",  $error));
        }

        $response = json_decode($result, true);

        if (!$response) {
            throw new \RuntimeException(sprintf('Empty response from %s', $url));
        }

        if ($response['code'] >= 400 && $response['code'] <= 500) {
            throw new \RuntimeException(
                sprintf('Error: Code=%s, message=%s',
                    $response['code'],
                    $response['response']['error']['message']
                )
            );
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