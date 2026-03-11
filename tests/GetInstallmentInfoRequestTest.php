<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\GetInstallmentInfoRequest;
use Keune\OmnipayLidio\Message\GetInstallmentInfoResponse;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GetInstallmentInfoRequestTest extends TestCase
{
    private GetInstallmentInfoRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetInstallmentInfoRequest(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );
        $this->request->setMerchantCode('MC');
        $this->request->setAuthorizationToken('AT');
        $this->request->setTestMode(true);
    }

    public function testGetData(): void
    {
        $this->request->setBin('43550843');
        $this->request->setAmount('100.00');
        $this->request->setPosId(2);
        $this->request->setCardCategory('CreditCards');

        $data = $this->request->getData();

        $this->assertSame('43550843', $data['BIN']);
        $this->assertSame(100.0, $data['amount']);
        $this->assertSame(2, $data['posId']);
        $this->assertSame('CreditCards', $data['cardCategory']);
    }

    public function testDefaults(): void
    {
        $this->request->setAmount('50.00');

        $data = $this->request->getData();

        $this->assertSame(0, $data['posId']);
        $this->assertSame('AllCards', $data['cardCategory']);
    }

    public function testResponseSuccess(): void
    {
        $posList = [['posId' => 1, 'posBankCode' => '0046']];
        $response = new GetInstallmentInfoResponse(
            $this->createStub(GetInstallmentInfoRequest::class),
            ['result' => 'Success', 'posList' => $posList]
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($posList, $response->getPosList());
    }

    public function testResponseNotFound(): void
    {
        $response = new GetInstallmentInfoResponse(
            $this->createStub(GetInstallmentInfoRequest::class),
            ['result' => 'NotFound']
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getPosList());
    }
}
