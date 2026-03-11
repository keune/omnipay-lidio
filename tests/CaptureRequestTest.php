<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\CaptureRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CaptureRequestTest extends TestCase
{
    private CaptureRequest $request;

    protected function setUp(): void
    {
        $this->request = new CaptureRequest(
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
        $this->request->setAmount('100.00');
        $this->request->setCurrency('TRY');
        $this->request->setClientIp('192.168.1.1');

        $data = $this->request->getData();

        $this->assertSame('order-123', $data['orderId']);
        $this->assertSame(100.0, $data['totalAmount']);
        $this->assertSame('TRY', $data['currency']);
        $this->assertSame('192.168.1.1', $data['clientIp']);
    }
}
