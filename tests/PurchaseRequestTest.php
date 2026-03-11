<?php

namespace Keune\OmnipayLidio\Tests;

use Keune\OmnipayLidio\Message\PurchaseRequest;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PurchaseRequestTest extends TestCase
{
    private PurchaseRequest $request;

    protected function setUp(): void
    {
        $this->request = new PurchaseRequest(
            $this->createStub(ClientInterface::class),
            new HttpRequest()
        );

        $this->request->setMerchantCode('TEST_MERCHANT');
        $this->request->setAuthorizationToken('TEST_TOKEN');
        $this->request->setTestMode(true);
    }

    public function testBaseUrlTest(): void
    {
        $this->assertSame('https://test.lidio.com/api', $this->request->getBaseUrl());
    }

    public function testBaseUrlProd(): void
    {
        $this->request->setTestMode(false);
        $this->assertSame('https://lidio.com/api', $this->request->getBaseUrl());
    }

    public function testGetDataWithNewCard(): void
    {
        $this->request->setTransactionId('order-123');
        $this->request->setAmount('100.00');
        $this->request->setCurrency('TRY');
        $this->request->setClientIp('127.0.0.1');
        $this->request->setClientPort('1234');
        $this->request->setCard([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'number' => '4355084355084358',
            'expiryMonth' => '12',
            'expiryYear' => '2026',
            'cvv' => '000',
        ]);
        $this->request->setCustomerInfo([
            'email' => 'john@example.com',
            'customerId' => '12345',
            'name' => 'John Doe',
            'phone' => '5555555555',
        ]);

        $data = $this->request->getData();

        $this->assertSame('order-123', $data['orderId']);
        $this->assertSame(100.0, $data['totalAmount']);
        $this->assertSame('TRY', $data['currency']);
        $this->assertSame('127.0.0.1', $data['clientIp']);
        $this->assertSame('1234', $data['clientPort']);
        $this->assertSame('Web', $data['clientType']);
        $this->assertSame('newCard', $data['paymentInstrument']);
        $this->assertSame('John Doe', $data['paymentInstrumentInfo']['newCard']['cardInfo']['cardHolderName']);
        $this->assertSame('4355084355084358', $data['paymentInstrumentInfo']['newCard']['cardInfo']['cardNumber']);
        $this->assertSame(12, $data['paymentInstrumentInfo']['newCard']['cardInfo']['lastMonth']);
        $this->assertSame(2026, $data['paymentInstrumentInfo']['newCard']['cardInfo']['lastYear']);
        $this->assertSame('000', $data['paymentInstrumentInfo']['newCard']['cvv']);
        $this->assertSame('sales', $data['paymentInstrumentInfo']['newCard']['processType']);
        $this->assertSame('john@example.com', $data['customerInfo']['email']);
    }

    public function testGetDataWithStoredCard(): void
    {
        $this->request->setTransactionId('order-456');
        $this->request->setAmount('50.00');
        $this->request->setCurrency('TRY');
        $this->request->setToken('card-token-123');

        $data = $this->request->getData();

        $this->assertSame('storedCard', $data['paymentInstrument']);
        $this->assertSame('card-token-123', $data['paymentInstrumentInfo']['storedCard']['cardToken']);
        $this->assertSame(50.0, $data['totalAmount']);
    }

    public function testGetDataWithRawPaymentInstrumentInfo(): void
    {
        $this->request->setTransactionId('order-789');
        $this->request->setAmount('75.00');
        $this->request->setCurrency('TRY');
        $this->request->setPaymentInstrument('newCard');
        $this->request->setPaymentInstrumentInfo([
            'newCard' => [
                'processType' => 'sales',
                'cardInfo' => ['cardNumber' => '1234567890123456'],
            ],
        ]);

        $data = $this->request->getData();

        $this->assertSame('newCard', $data['paymentInstrument']);
        $this->assertSame('1234567890123456', $data['paymentInstrumentInfo']['newCard']['cardInfo']['cardNumber']);
    }

    public function testAmountCastToFloat(): void
    {
        $this->request->setTransactionId('order-100');
        $this->request->setAmount('123.45');
        $this->request->setCurrency('TRY');
        $this->request->setToken('some-token');

        $data = $this->request->getData();

        $this->assertIsFloat($data['totalAmount']);
        $this->assertSame(123.45, $data['totalAmount']);
    }

    public function test3DSecureFlag(): void
    {
        $this->request->setUse3DSecure(true);
        $this->assertTrue($this->request->getUse3DSecure());

        $this->request->setUse3DSecure(false);
        $this->assertFalse($this->request->getUse3DSecure());
    }

    public function testInstallmentCount(): void
    {
        $this->request->setInstallmentCount(6);
        $this->assertSame(6, $this->request->getInstallmentCount());
    }

    public function testSaveAfterSuccess(): void
    {
        $this->request->setSaveAfterSuccess(true);
        $this->assertTrue($this->request->getSaveAfterSuccess());
    }

    public function testNotifyUrlMappedToNotificationUrl(): void
    {
        $this->request->setTransactionId('order-100');
        $this->request->setAmount('10.00');
        $this->request->setCurrency('TRY');
        $this->request->setToken('t');
        $this->request->setNotifyUrl('https://example.com/notify');

        $data = $this->request->getData();

        $this->assertSame('https://example.com/notify', $data['notificationUrl']);
        $this->assertArrayNotHasKey('notifyUrl', $data);
    }

    public function testBasketItemsInData(): void
    {
        $this->request->setTransactionId('order-100');
        $this->request->setAmount('10.00');
        $this->request->setCurrency('TRY');
        $this->request->setToken('t');
        $items = [['name' => 'Product 1', 'unitPrice' => 10]];
        $this->request->setBasketItems($items);

        $data = $this->request->getData();

        $this->assertSame($items, $data['basketItems']);
    }

    public function testAddressesInData(): void
    {
        $this->request->setTransactionId('order-100');
        $this->request->setAmount('10.00');
        $this->request->setCurrency('TRY');
        $this->request->setToken('t');
        $invoice = ['contactName' => 'John', 'city' => 'Istanbul'];
        $delivery = ['contactName' => 'Jane', 'city' => 'Ankara'];
        $this->request->setInvoiceAddress($invoice);
        $this->request->setDeliveryAddress($delivery);

        $data = $this->request->getData();

        $this->assertSame($invoice, $data['invoiceAddress']);
        $this->assertSame($delivery, $data['deliveryAddress']);
    }
}
