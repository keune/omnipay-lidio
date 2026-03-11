<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\PurchaseRequest;
use Keune\OmnipayLidio\Message\PurchaseResponse;
use PHPUnit\Framework\TestCase;

class PurchaseResponseTest extends TestCase
{
    private function makeResponse(array $data): PurchaseResponse
    {
        $request = $this->createStub(PurchaseRequest::class);
        return new PurchaseResponse($request, $data);
    }

    public function testSuccessfulResponse(): void
    {
        $response = $this->makeResponse([
            'result' => 'Success',
            'resultMessage' => 'Operation completed',
            'resultDetail' => 'Success',
            'paymentInfo' => [
                'orderId' => 'order-123',
                'systemTransId' => 'sys-456',
            ],
        ]);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Operation completed', $response->getMessage());
        $this->assertSame('Success', $response->getCode());
        $this->assertSame('Success', $response->getResultDetail());
        $this->assertSame('order-123', $response->getTransactionId());
        $this->assertSame('sys-456', $response->getTransactionReference());
        $this->assertSame('sys-456', $response->getSystemTransId());
    }

    public function testFailedResponse(): void
    {
        $response = $this->makeResponse([
            'result' => 'SystemError',
            'resultMessage' => 'Card declined',
        ]);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Card declined', $response->getMessage());
        $this->assertSame('SystemError', $response->getCode());
    }

    public function testRedirectFormCreatedResponse(): void
    {
        $response = $this->makeResponse([
            'result' => 'RedirectFormCreated',
            'resultDetail' => 'ThreeDSRedirectFormCreated',
            'redirectForm' => '<form action="https://3ds.example.com/auth">...</form>',
            'redirectFormParams' => [
                'actionLink' => 'https://3ds.example.com/auth',
                'paramList' => [
                    ['name' => 'PaReq', 'value' => 'abc123'],
                    ['name' => 'TermUrl', 'value' => 'https://merchant.com/return'],
                ],
            ],
            'paymentInfo' => [
                'systemTransId' => 'sys-789',
            ],
        ]);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('https://3ds.example.com/auth', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame([
            'PaReq' => 'abc123',
            'TermUrl' => 'https://merchant.com/return',
        ], $response->getRedirectData());
        $this->assertStringContainsString('<form', $response->getRedirectForm());
        $this->assertSame('sys-789', $response->getSystemTransId());
    }

    public function testVerificationRequiredResponse(): void
    {
        $response = $this->makeResponse([
            'result' => 'VerificationRequired',
            'resultDetail' => 'CVVRequired',
            'paymentInfo' => [
                'systemTransId' => 'sys-100',
            ],
        ]);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->isVerificationRequired());
        $this->assertSame('CVVRequired', $response->getResultDetail());
        $this->assertSame('sys-100', $response->getSystemTransId());
    }

    public function testNotRedirectWhenFailed(): void
    {
        $response = $this->makeResponse([
            'result' => 'InvalidParameter',
        ]);

        $this->assertFalse($response->isRedirect());
    }

    public function testResultMessageOnFailure(): void
    {
        $response = $this->makeResponse([
            'result' => 'InvalidCredential',
            'resultMessage' => 'Invalid credentials provided',
        ]);

        $this->assertSame('Invalid credentials provided', $response->getMessage());
    }

    public function testMissingDataReturnsNull(): void
    {
        $response = $this->makeResponse([]);

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getResultDetail());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getRedirectForm());
    }

    public function testFallbackToRootLevelTransactionFields(): void
    {
        // Some endpoints may return systemTransId/orderId at root level
        $response = $this->makeResponse([
            'result' => 'Success',
            'systemTransId' => 'sys-root',
            'orderId' => 'order-root',
        ]);

        $this->assertSame('sys-root', $response->getTransactionReference());
        $this->assertSame('order-root', $response->getTransactionId());
    }
}
