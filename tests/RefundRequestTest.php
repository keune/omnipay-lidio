<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\RefundRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RefundRequestTest extends TestCase
{
    private RefundRequest $request;

    protected function setUp(): void
    {
        $this->request = new RefundRequest(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );
        $this->request->setMerchantCode('MC');
        $this->request->setAuthorizationToken('AT');
        $this->request->setTestMode(true);
    }

    public function testGetData(): void
    {
        $this->request->setRefundTransId('0001');
        $this->request->setTransactionId('order-123');
        $this->request->setAmount('50.00');
        $this->request->setCurrency('TRY');
        $this->request->setClientIp('10.0.0.1');

        $data = $this->request->getData();

        $this->assertSame('0001', $data['refundTransId']);
        $this->assertSame('order-123', $data['orderId']);
        $this->assertSame(50.0, $data['totalAmount']);
        $this->assertSame('TRY', $data['currency']);
        $this->assertSame('10.0.0.1', $data['clientIp']);
    }
}
