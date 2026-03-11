<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\FetchTransactionRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class FetchTransactionRequestTest extends TestCase
{
    private FetchTransactionRequest $request;

    protected function setUp(): void
    {
        $this->request = new FetchTransactionRequest(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );
        $this->request->setMerchantCode('MC');
        $this->request->setAuthorizationToken('AT');
        $this->request->setTestMode(true);
    }

    public function testGetDataDefaults(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setAmount('40.00');

        $data = $this->request->getData();

        $this->assertSame('order-123', $data['orderId']);
        $this->assertSame(40.0, $data['totalAmount']);
        $this->assertSame('newCard', $data['paymentInstrument']);
        $this->assertSame('sales', $data['paymentInquiryInstrumentInfo']['card']['processType']);
        $this->assertSame(1, $data['paymentInquiryInstrumentInfo']['card']['posAccount']['id']);
    }

    public function testGetDataCustom(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setAmount('40.00');
        $this->request->setPaymentInstrument('storedCard');
        $this->request->setPosAccountId(5);
        $this->request->setProcessType('preAuth');

        $data = $this->request->getData();

        $this->assertSame('storedCard', $data['paymentInstrument']);
        $this->assertSame('preAuth', $data['paymentInquiryInstrumentInfo']['card']['processType']);
        $this->assertSame(5, $data['paymentInquiryInstrumentInfo']['card']['posAccount']['id']);
    }

    public function testGetDataRawInstrumentInfo(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setAmount('40.00');
        $info = ['card' => ['processType' => 'custom']];
        $this->request->setPaymentInquiryInstrumentInfo($info);

        $data = $this->request->getData();

        $this->assertSame($info, $data['paymentInquiryInstrumentInfo']);
    }
}
