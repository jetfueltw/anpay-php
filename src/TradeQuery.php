<?php

namespace Jetfuel\Anpay;

use Jetfuel\Anpay\Traits\ResultParser;

class TradeQuery extends Payment
{
    use ResultParser;


    /**
     * constructor.
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
     * Find Order by trade number.
     *
     * @param string $tradeNo
     * @return array|null
     */
    public function find($tradeNo)
    {
        $payload = $this->signQueryPayload([
            'orderNo'           => $tradeNo,
        ]);
        
        $order = $this->parseQueryResponse($this->httpClient->post('anpay/payQuery', $payload));
        
        
        if ($order['key'] !== '00' && $order['key'] !== '05') {
            return null;
        }

        $result = json_decode($order['result'], true);
        $result['amount'] = $this->convertFenToYuan( $result['amount']);
        $order['result'] = json_encode($result);
        
        return $order;
    }

    /**
     * Is order already paid.
     *
     * @param string $tradeNo
     * @return bool
     */
    public function isPaid($tradeNo)
    {
        $order = $this->find($tradeNo);

        if ($order === null || !isset($order['result']) || json_decode($order['result'], true)['payment_status'] !=='1'  ) {
            return false;
        }

        return true;
    }
}
