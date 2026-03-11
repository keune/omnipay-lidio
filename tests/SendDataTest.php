<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\VoidRequest;
use Keune\OmnipayLidio\Message\VoidResponse;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class SendDataTest extends TestCase
{
    public function testSendDataPostsJsonAndReturnsResponse(): void
    {
        $body = $this->createStub(StreamInterface::class);
        $body->method('__toString')->willReturn(json_encode([
            'result' => 'Success',
            'resultMessage' => 'Operation completed',
            'orderId' => 'order-123',
        ]));

        $httpResponse = $this->createStub(ResponseInterface::class);
        $httpResponse->method('getBody')->willReturn($body);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://test.lidio.com/api/Cancel',
                $this->callback(function (array $headers) {
                    return $headers['Content-Type'] === 'application/json'
                        && $headers['Accept'] === 'application/json'
                        && $headers['MerchantCode'] === 'MC'
                        && $headers['Authorization'] === 'AT';
                }),
                $this->callback(function (string $jsonBody) {
                    $data = json_decode($jsonBody, true);
                    return $data['orderId'] === 'order-123';
                })
            )
            ->willReturn($httpResponse);

        $request = new VoidRequest($httpClient, new HttpRequest());
        $request->setMerchantCode('MC');
        $request->setAuthorizationToken('AT');
        $request->setTestMode(true);
        $request->setTransactionId('order-123');

        $response = $request->send();

        $this->assertInstanceOf(VoidResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('order-123', $response->getTransactionId());
    }

    public function testSendDataUsesProductionUrl(): void
    {
        $body = $this->createStub(StreamInterface::class);
        $body->method('__toString')->willReturn('{"result":"Success"}');

        $httpResponse = $this->createStub(ResponseInterface::class);
        $httpResponse->method('getBody')->willReturn($body);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://lidio.com/api/Cancel',
                $this->anything(),
                $this->anything()
            )
            ->willReturn($httpResponse);

        $request = new VoidRequest($httpClient, new HttpRequest());
        $request->setMerchantCode('MC');
        $request->setAuthorizationToken('AT');
        $request->setTestMode(false);
        $request->setTransactionId('order-123');

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
    }
}
