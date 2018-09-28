<?php

namespace Jetfuel\Anpay;

use Jetfuel\Anpay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const QRCODE_IMG_PREFIX = 'IMG|';

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $notifyUrl
     * @return array|null
     */
    public function order($tradeNo, $channel, $amount, $notifyUrl)
    {//只支持支付寶 $channel用不到
        $payload = $this->signPayload([
            'orderNo'    => $tradeNo,
            'product_name'    => 'GOODS_NAME',
            'body'      => 'GOODS_BODY',
            'amount'    => $amount,
            'notifyUrl' => $notifyUrl,
            'pz_userId' => $tradeNo,
            'mch_create_ip' => '192.168.1.1',
        ]);

        //目前只支持支付寶掃碼 
        $result = $this->parseResponse($this->httpClient->post('anpay/pay', $payload));
        $uri = $result['postUrl'];
        unset($result['postUrl']);

        $qrcode = $uri . $this->parseForwardResponse($this->httpClient->postUri($uri, $result));
           
        if (isset($qrcode)) {
            $result['qrcodeUrl'] = self::QRCODE_IMG_PREFIX . $qrcode;

            return $result;
        }

        return ['qrcodeUrl' => 'error'];
    }
}
