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

        var_dump($result);

        $this->assertContains('IMG|', $result['qrcodeUrl'], '', true);

        return $tradeNo;
    }

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'mid'         => '68',
            'code'        => 'SUCCEED',
            'msg'         => 'OK',
            'orderNo'     => '201809051111',
            'amount'      => '10',
            'orderTime'   => '2018-09-05 18:00:00',
            'sign'        => '2fdbd6ad0645b615da8ce864babea51c',
        ];

        $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'mid'         => '68',
            'code'        => 'SUCCEED',
            'msg'         => 'OK',
            'orderNo'     => '201809051111',
            'amount'      => '10',
            'orderTime'   => '2018-09-05 18:00:00',
            'sign'        => '2fdbd6ad0645b615da8ce864babea51c',
        ];

        $this->assertEquals([
            'mid'         => '68',
            'code'        => 'SUCCEED',
            'msg'         => 'OK',
            'orderNo'     => '201809051111',
            'amount'      => '10',
            'orderTime'   => '2018-09-05 18:00:00',
            'sign'        => '2fdbd6ad0645b615da8ce864babea51c',
        ], $mock->parseNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('success', $mock->successNotifyResponse());
    }
}
