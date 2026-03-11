<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\CompletePurchaseRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private CompletePurchaseRequest $request;

    protected function setUp(): void
    {
        $this->request = new CompletePurchaseRequest(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );
        $this->request->setMerchantCode('MC');
        $this->request->setAuthorizationToken('AT');
        $this->request->setTestMode(true);
    }

    public function testGetData(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setTransactionReference('sys-456');
        $this->request->setAmount('100.00');
        $this->request->setCurrency('TRY');
        $this->request->setPaymentInstrument('newCard');

        $data = $this->request->getData();

        $this->assertSame('order-123', $data['orderId']);
        $this->assertSame('sys-456', $data['systemTransId']);
        $this->assertSame(100.0, $data['totalAmount']);
        $this->assertSame('TRY', $data['currency']);
        $this->assertSame('newCard', $data['paymentInstrument']);
        $this->assertArrayHasKey('paymentInstrumentInfo', $data);
        $this->assertArrayHasKey('newCard', $data['paymentInstrumentInfo']);
        $this->assertArrayHasKey('clientType', $data);
    }

    public function testCustomPaymentInstrumentInfo(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setTransactionReference('sys-456');
        $this->request->setAmount('50.00');
        $this->request->setCurrency('TRY');
        $info = ['storedCard' => ['cvv' => '123', 'otp' => '999999']];
        $this->request->setPaymentInstrumentInfo($info);

        $data = $this->request->getData();

        $this->assertSame($info, $data['paymentInstrumentInfo']);
    }

    public function testDefaultPaymentInstrument(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setTransactionReference('sys-456');
        $this->request->setAmount('50.00');
        $this->request->setCurrency('TRY');

        $data = $this->request->getData();

        $this->assertSame('newCard', $data['paymentInstrument']);
    }

    public function testDefaultInstrumentInfoMatchesSelectedInstrument(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setTransactionReference('sys-456');
        $this->request->setAmount('50.00');
        $this->request->setCurrency('TRY');
        $this->request->setPaymentInstrument('storedCard');

        $data = $this->request->getData();

        $this->assertArrayHasKey('storedCard', $data['paymentInstrumentInfo']);
        $this->assertArrayNotHasKey('newCard', $data['paymentInstrumentInfo']);
    }

    public function testOptionalFieldsIncludedWhenSet(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setTransactionReference('sys-456');
        $this->request->setAmount('50.00');
        $this->request->setCurrency('TRY');
        $this->request->setClientIp('1.2.3.4');
        $this->request->setClientType('Android');
        $this->request->setCustomParameters('MOTO:true');

        $data = $this->request->getData();

        $this->assertSame('1.2.3.4', $data['clientIp']);
        $this->assertSame('Android', $data['clientType']);
        $this->assertSame('MOTO:true', $data['customParameters']);
    }
}
