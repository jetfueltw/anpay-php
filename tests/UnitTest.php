<?php

namespace Test;

use Faker\Factory;
use Jetfuel\Anpay\Constants\Channel;
use Jetfuel\Anpay\DigitalPayment;
use Jetfuel\Anpay\QuickPayment;
use Jetfuel\Anpay\TradeQuery;
use Jetfuel\Anpay\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantId;
    private $secretKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_ID');
        $this->secretKey = getenv('SECRET_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = $tradeNo = date('YmdHis').rand(1000, 9999);
        $channel = Channel::ALIPAY;
        $amount = 10;
        $notifyUrl = 'http://www.yahoo.com';//$faker->url;

        $payment = new DigitalPayment($this->merchantId, $this->secretKey);
        $result = $payment->order($tradeNo, $channel, $amount, $notifyUrl);

        //var_dump($result);

        $this->assertContains('IMG|', $result['qrcodeUrl'], '', true);

        return $tradeNo;
    }

    // public function testNotifyWebhookVerifyNotifyPayload()
    // {
    //     $mock = $this->getMockForTrait(NotifyWebhook::class);

    //     $payload = [
    //         'mchid'       => '4794',
    //         'fee'         => '3000',
    //         'pdorderid'   => '201809051111',
    //         'orderid'     => '201809051111',
    //         'unit'        => 'fen',
    //         'status'      => 'success',
    //         'paychannel'  => 'alipay',
    //         'time'        => '2018-09-05 18:00:00',
    //         'sign'        => '31b932118979473a3c4bc264cbdb8c6b',
    //         'gameSign'    => '265f3d6c74ecee136beefb0eea9e50e1',
    //     ];

    //     $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey));
    // }

    // public function testNotifyWebhookParseNotifyPayload()
    // {
    //     $mock = $this->getMockForTrait(NotifyWebhook::class);

    //     $payload = [
    //         'mchid'       => '4794',
    //         'fee'         => '3000',
    //         'pdorderid'   => '201809051111',
    //         'orderid'     => '201809051111',
    //         'unit'        => 'fen',
    //         'status'      => 'success',
    //         'paychannel'  => 'alipay',
    //         'time'        => '2018-09-05 18:00:00',
    //         'sign'        => '31b932118979473a3c4bc264cbdb8c6b',
    //         'gameSign'    => '265f3d6c74ecee136beefb0eea9e50e1',
    //     ];

    //     $this->assertEquals([
    //         'mchid'       => '4794',
    //         'fee'         => '3000',
    //         'pdorderid'   => '201809051111',
    //         'orderid'     => '201809051111',
    //         'unit'        => 'fen',
    //         'status'      => 'success',
    //         'paychannel'  => 'alipay',
    //         'time'        => '2018-09-05 18:00:00',
    //         'sign'        => '31b932118979473a3c4bc264cbdb8c6b',
    //         'gameSign'    => '265f3d6c74ecee136beefb0eea9e50e1',
    //     ], $mock->parseNotifyPayload($payload, $this->secretKey));
    // }

    // public function testNotifyWebhookSuccessNotifyResponse()
    // {
    //     $mock = $this->getMockForTrait(NotifyWebhook::class);

    //     $this->assertEquals('success', $mock->successNotifyResponse());
    // }
}
