<?php

namespace Jetfuel\Anpay;

class Signature
{
    /**
     * Generate signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generate(array $payload, $secretKey)
    {
        $baseString = self::md5Hash($secretKey) . self::buildBaseString($payload);

        return self::md5Hash($baseString);
    }

    /**
     * Generate notify signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generateNotify(array $payload, $secretKey)
    {
        $baseString = self::buildBaseNotifyString($payload).$secretKey;

        return self::md5Hash($baseString);
    }

    /**
     * @param array $payload
     * @param string $secretKey
     * @param string $signature
     * @return bool
     */
    public static function validate(array $payload, $secretKey, $signature)
    {
        return self::generate($payload, $secretKey) === $signature;
    }

    public static function validateNotify(array $payload, $secretKey, $signature)
    {
        return self::generateNotify($payload, $secretKey) === $signature;
    }

    private static function buildBaseString(array $payload)
    {
        return $payload['mid'] . $payload['orderNo'] . $payload['amount'];
    }

    private static function buildBaseNotifyString(array $payload)
    {
        // return $payload['mchid'] . $payload['orderid'] .$payload['fee'] . 'fen' . $payload['status'] .$payload['paychannel'];
        //因為gameSign才有key比較安全, 所以對gameSign做驗證
        return $payload['mchid'] . $payload['orderid'] . $payload['paychannel'] . $payload['status'];
    }

    private static function md5Hash($data)
    {
        return md5($data);
    }
}
