<?php

namespace Jetfuel\Anpay;

use Jetfuel\Anpay\HttpClient\GuzzleHttpClient;
use Jetfuel\Anpay\Traits\ConvertMoney;

class Payment
{
    use ConvertMoney;

    const BASE_API_URL      = 'http://43.249.80.192/';

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Anpay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return array
     */
    protected function signPayload(array $payload)
    {
        $payload['mid'] = $this->merchantId;
        $payload['sign'] = Signature::generate($payload, $this->secretKey);

        return $payload;
    }

}
