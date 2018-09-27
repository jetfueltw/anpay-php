<?php

namespace Jetfuel\Anpay\Traits;

use Jetfuel\Anpay\Signature;

trait NotifyWebhook
{
    /**
     * Verify notify request's signature.
     *
     * @param array $payload
     * @param $secretKey
     * @return bool
     */
    public function verifyNotifyPayload(array $payload, $secretKey)
    {
        if (!isset($payload['gameSign'])) {
            return false;
        }

        $signature = $payload['gameSign'];

        return Signature::validateNotify($payload, $secretKey, $signature);
    }

    /**
     * Verify notify request's signature and parse payload.
     *
     * @param array $payload
     * @param string $secretKey
     * @return array|null
     */
    public function parseNotifyPayload(array $payload, $secretKey)
    {
        if (!$this->verifyNotifyPayload($payload, $secretKey)) {
            return null;
        }

        return $payload;
    }

    /**
     * Response content for successful notify.
     *
     * @return string
     */
    public function successNotifyResponse()
    {
        return 'success';
    }
}
