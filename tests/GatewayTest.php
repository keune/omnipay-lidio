<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Gateway;
use Keune\OmnipayLidio\Message\CaptureRequest;
use Keune\OmnipayLidio\Message\CompletePurchaseRequest;
use Keune\OmnipayLidio\Message\CreateCardRequest;
use Keune\OmnipayLidio\Message\DeleteCardRequest;
use Keune\OmnipayLidio\Message\FetchCardsRequest;
use Keune\OmnipayLidio\Message\FetchTransactionRequest;
use Keune\OmnipayLidio\Message\GetInstallmentInfoRequest;
use Keune\OmnipayLidio\Message\PurchaseRequest;
use Keune\OmnipayLidio\Message\RefundRequest;
use Keune\OmnipayLidio\Message\VoidRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class GatewayTest extends TestCase
{
    private Gateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new Gateway(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );

        $this->gateway->setMerchantCode('TEST_MERCHANT');
        $this->gateway->setAuthorizationToken('TEST_TOKEN');
        $this->gateway->setMerchantKey('TEST_KEY');
        $this->gateway->setApiPassword('TEST_PASS');
        $this->gateway->setTestMode(true);
    }

    public function testGetName(): void
    {
        $this->assertSame('Lidio', $this->gateway->getName());
    }

    public function testDefaultParameters(): void
    {
        $defaults = $this->gateway->getDefaultParameters();

        $this->assertArrayHasKey('merchantCode', $defaults);
        $this->assertArrayHasKey('authorizationToken', $defaults);
        $this->assertArrayHasKey('merchantKey', $defaults);
        $this->assertArrayHasKey('apiPassword', $defaults);
        $this->assertArrayHasKey('testMode', $defaults);
        $this->assertTrue($defaults['testMode']);
    }

    public function testCredentials(): void
    {
        $this->assertSame('TEST_MERCHANT', $this->gateway->getMerchantCode());
        $this->assertSame('TEST_TOKEN', $this->gateway->getAuthorizationToken());
        $this->assertSame('TEST_KEY', $this->gateway->getMerchantKey());
        $this->assertSame('TEST_PASS', $this->gateway->getApiPassword());
    }

    public function testPurchaseReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->purchase(['amount' => '10.00', 'currency' => 'TRY']);
        $this->assertInstanceOf(PurchaseRequest::class, $request);
    }

    public function testCompletePurchaseReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf(CompletePurchaseRequest::class, $request);
    }

    public function testCaptureReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->capture();
        $this->assertInstanceOf(CaptureRequest::class, $request);
    }

    public function testVoidReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->void();
        $this->assertInstanceOf(VoidRequest::class, $request);
    }

    public function testRefundReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->refund();
        $this->assertInstanceOf(RefundRequest::class, $request);
    }

    public function testFetchTransactionReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->fetchTransaction();
        $this->assertInstanceOf(FetchTransactionRequest::class, $request);
    }

    public function testCreateCardReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->createCard();
        $this->assertInstanceOf(CreateCardRequest::class, $request);
    }

    public function testDeleteCardReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->deleteCard();
        $this->assertInstanceOf(DeleteCardRequest::class, $request);
    }

    public function testGetInstallmentInfoReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->getInstallmentInfo();
        $this->assertInstanceOf(GetInstallmentInfoRequest::class, $request);
    }

    public function testFetchCardsReturnsCorrectRequestClass(): void
    {
        $request = $this->gateway->fetchCards();
        $this->assertInstanceOf(FetchCardsRequest::class, $request);
    }

    public function testCredentialsPropagateToRequests(): void
    {
        $request = $this->gateway->purchase(['amount' => '10.00', 'currency' => 'TRY']);

        $this->assertSame('TEST_MERCHANT', $request->getMerchantCode());
        $this->assertSame('TEST_TOKEN', $request->getAuthorizationToken());
    }

    public function testTestModePropagates(): void
    {
        $request = $this->gateway->purchase(['amount' => '10.00', 'currency' => 'TRY']);
        $this->assertTrue($request->getTestMode());

        $this->gateway->setTestMode(false);
        $request = $this->gateway->purchase(['amount' => '10.00', 'currency' => 'TRY']);
        $this->assertFalse($request->getTestMode());
    }
}
