<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\VoidRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class VoidRequestTest extends TestCase
{
    private VoidRequest $request;

    protected function setUp(): void
    {
        $this->request = new VoidRequest(
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

        $data = $this->request->getData();

        $this->assertSame(['orderId' => 'order-123'], $data);
    }

    public function testFallbackToOrderId(): void
    {
        $this->request->setOrderId('order-alt');

        $data = $this->request->getData();

        $this->assertSame('order-alt', $data['orderId']);
    }
}
