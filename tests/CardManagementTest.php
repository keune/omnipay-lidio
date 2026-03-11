<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\CreateCardRequest;
use Keune\OmnipayLidio\Message\CreateCardResponse;
use Keune\OmnipayLidio\Message\DeleteCardRequest;
use Keune\OmnipayLidio\Message\FetchCardsRequest;
use Keune\OmnipayLidio\Message\FetchCardsResponse;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CardManagementTest extends TestCase
{
    private function makeRequest(string $class): mixed
    {
        $request = new $class(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );
        $request->setMerchantCode('MC');
        $request->setAuthorizationToken('AT');
        $request->setTestMode(true);
        return $request;
    }

    public function testCreateCardGetData(): void
    {
        $request = $this->makeRequest(CreateCardRequest::class);
        $request->setCard([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'number' => '4355084355084358',
            'expiryMonth' => '12',
            'expiryYear' => '2026',
        ]);
        $request->setCustomerInfo([
            'customerID' => '12345',
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'phone' => '5555555555',
        ]);
        $request->setVerificationOtp('123456');
        $request->setClientIp('127.0.0.1');

        $data = $request->getData();

        $this->assertSame('John Doe', $data['cardHolderName']);
        $this->assertSame('4355084355084358', $data['cardNumber']);
        $this->assertSame(12, $data['cardMonth']);
        $this->assertSame(2026, $data['cardYear']);
        $this->assertSame('123456', $data['verificationOtp']);
        $this->assertSame('127.0.0.1', $data['clientIP']);
        $this->assertSame('12345', $data['customerInfo']['customerID']);
    }

    public function testCreateCardResponse(): void
    {
        $response = new CreateCardResponse(
            $this->createStub(CreateCardRequest::class),
            ['result' => 'Success', 'cardToken' => 'tok-abc-123']
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('tok-abc-123', $response->getCardReference());
    }

    public function testCreateCardResponseNoToken(): void
    {
        $response = new CreateCardResponse(
            $this->createStub(CreateCardRequest::class),
            ['result' => 'InvalidCredential']
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getCardReference());
    }

    public function testDeleteCardGetData(): void
    {
        $request = $this->makeRequest(DeleteCardRequest::class);
        $request->setCardReference('tok-abc-123');
        $request->setCustomerId('12345');
        $request->setClientIp('127.0.0.1');

        $data = $request->getData();

        $this->assertSame('tok-abc-123', $data['cardToken']);
        $this->assertSame('12345', $data['customerId']);
        $this->assertSame('127.0.0.1', $data['clientIp']);
    }

    public function testFetchCardsGetData(): void
    {
        $request = $this->makeRequest(FetchCardsRequest::class);
        $request->setCustomerId('12345');
        $request->setPhone('5555555555');
        $request->setEmail('john@example.com');
        $request->setClientIp('127.0.0.1');

        $data = $request->getData();

        $this->assertSame('12345', $data['customerId']);
        $this->assertSame('5555555555', $data['phone']);
        $this->assertSame('john@example.com', $data['email']);
        $this->assertSame('127.0.0.1', $data['clientIp']);
    }

    public function testFetchCardsResponse(): void
    {
        $cards = [
            ['cardToken' => 'tok-1', 'maskedCardNumber' => '4355****4358'],
            ['cardToken' => 'tok-2', 'maskedCardNumber' => '5400****1234'],
        ];
        $response = new FetchCardsResponse(
            $this->createStub(FetchCardsRequest::class),
            ['result' => 'Success', 'cardList' => $cards]
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertCount(2, $response->getCards());
        $this->assertSame('tok-1', $response->getCards()[0]['cardToken']);
    }

    public function testFetchCardsResponseEmpty(): void
    {
        $response = new FetchCardsResponse(
            $this->createStub(FetchCardsRequest::class),
            ['result' => 'CardNotFound']
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getCards());
    }
}
