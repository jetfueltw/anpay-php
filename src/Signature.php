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
     * Generate query signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generateQuery(array $payload, $secretKey)
    {
        $baseString = self::md5Hash($secretKey) . self::buildQueryBaseString($payload);

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

    private static function buildQueryBaseString(array $payload)
    {
        return $payload['mid'] . $payload['orderNo'] ;
    }


    private static function md5Hash($data)
    {
        return md5($data);
    }
}
