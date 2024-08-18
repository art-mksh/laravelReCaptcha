<?php

namespace ArtMksh\ReCaptchaV2;

use Symfony\Component\HttpFoundation\Request as Request;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\GuzzleException as Exception;

class ReCaptchaV2Verification
{
    /**
     * Google recaptcha api verification url
     */
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    /**
     * Google recaptcha secret key
     *
     * @var string
     */
    private readonly string $secretKey;
    /**
     * Request sending client
     *
     * @var Client
     */
    private readonly Client $http;
    private readonly int $tryCount;

    /**
     * @param string $secretKey
     * @param array $options
     * @param int $tryCount
     */
    public function __construct(string $secretKey, array $options = [], int $tryCount = 2)
    {
        $this->secretKey = $secretKey;
        $this->http = new Client($options);
        $this->tryCount = $tryCount;
    }

    /**
     * Verify response.
     *
     * @param string $response
     * @param string|null $initialIp
     * @return bool
     */
    public function verifyResponse(string $response, string $initialIp = null): bool
    {
        return !empty($response) && $this->verifyTry($this->secretKey, $response, $initialIp);
    }

    /**
     * Try to send verification
     *
     * @param string $secret
     * @param string $response
     * @param string $initialIp
     * @return bool
     */
    protected function verifyTry(string $secret, string $response, string $initialIp): bool
    {
        $result = false;

        if (!empty($tryCount)) {
            for ($i = 0; $i < $this->tryCount; $i++) {
                $verifyResponse = $this->sendRequestVerify([
                    'secret' => $this->secretKey,
                    'response' => $response,
                    'initial_ip' => $initialIp,
                ]);

                if (!empty($verifyResponse['success']) && $verifyResponse['success'] === true) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Verification request sending
     *
     * @param array $query
     * @return mixed
     */
    protected function sendRequestVerify(array $query = []): mixed
    {
        $response = $this->http->request('POST', static::VERIFY_URL, [
            'form_params' => $query,
        ]);

        return json_decode($response->getBody(), true);
    }
}
