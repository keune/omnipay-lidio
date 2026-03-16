<?php

namespace Keune\OmnipayLidio;

use Keune\OmnipayLidio\Exception\InvalidTdsDataException;
use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    use CredentialsTrait;

    public function getName(): string
    {
        return 'Lidio';
    }

    public function getDefaultParameters(): array
    {
        return [
            'merchantCode' => '',
            'authorizationToken' => '',
            'merchantKey' => '',
            'apiPassword' => '',
            'testMode' => true,
        ];
    }

    public function purchase(array $options = []): Message\PurchaseRequest
    {
        return $this->createRequest(Message\PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = []): Message\CompletePurchaseRequest
    {
        return $this->createRequest(Message\CompletePurchaseRequest::class, $options);
    }

    public function capture(array $options = []): Message\CaptureRequest
    {
        return $this->createRequest(Message\CaptureRequest::class, $options);
    }

    public function void(array $options = []): Message\VoidRequest
    {
        return $this->createRequest(Message\VoidRequest::class, $options);
    }

    public function refund(array $options = []): Message\RefundRequest
    {
        return $this->createRequest(Message\RefundRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): Message\FetchTransactionRequest
    {
        return $this->createRequest(Message\FetchTransactionRequest::class, $options);
    }

    public function createCard(array $options = []): Message\CreateCardRequest
    {
        return $this->createRequest(Message\CreateCardRequest::class, $options);
    }

    public function deleteCard(array $options = []): Message\DeleteCardRequest
    {
        return $this->createRequest(Message\DeleteCardRequest::class, $options);
    }

    public function getInstallmentInfo(array $options = []): Message\GetInstallmentInfoRequest
    {
        return $this->createRequest(Message\GetInstallmentInfoRequest::class, $options);
    }

    public function fetchCards(array $options = []): Message\FetchCardsRequest
    {
        return $this->createRequest(Message\FetchCardsRequest::class, $options);
    }

    public function getBankOfBinNumber(array $options = []): Message\GetBankOfBinNumberRequest
    {
        return $this->createRequest(Message\GetBankOfBinNumberRequest::class, $options);
    }

    /**
     * Validate the 3DS callback data received at the return URL.
     *
     * Call this after the user is redirected back from 3DS and before calling completePurchase().
     * Validates that the Result is '3DSuccess', the amount and order ID match, and the hash is correct.
     *
     * @param array  $callbackData       The query string parameters from the return URL
     *                                   (OrderId, SystemTransId, Result, TotalAmount, InstallmentCount, MDStatus, Hash)
     * @param string $expectedAmount     The expected payment amount (e.g. '100.00')
     * @param string $expectedOrderId    The expected order ID
     * @param string $customerIdentifier The customer email or ID used in hash (depends on merchant settings)
     *
     * @return array The validated callback data
     *
     * @throws InvalidTdsDataException
     */
    public function validateTdsPostData(
        array $callbackData,
        string $expectedAmount,
        string $expectedOrderId,
        string $customerIdentifier,
    ): array {
        $result = $callbackData['Result'] ?? null;
        $orderId = $callbackData['OrderId'] ?? null;
        $totalAmount = $callbackData['TotalAmount'] ?? null;
        $hash = $callbackData['Hash'] ?? null;

        if ('3DSuccess' !== $result) {
            throw new InvalidTdsDataException(
                '3DS verification failed. Result: '.($result ?? 'missing')
            );
        }

        if ($orderId !== $expectedOrderId) {
            throw new InvalidTdsDataException(
                'Order ID mismatch. Expected: '.$expectedOrderId.', Got: '.($orderId ?? 'missing')
            );
        }

        // Compare amounts as floats formatted to 2 decimal places
        $formattedExpected = number_format((float) $expectedAmount, 2, '.', '');
        $formattedCallback = number_format((float) $totalAmount, 2, '.', '');

        if ($formattedExpected !== $formattedCallback) {
            throw new InvalidTdsDataException(
                'Amount mismatch. Expected: '.$formattedExpected.', Got: '.$formattedCallback
            );
        }

        // Verify hash: SHA256+Base64(OrderId:MerchantKey:TotalAmount:Result:CustomerIdentifier)
        $hashData = $orderId.':'.$this->getMerchantKey().':'.$formattedCallback.':'.$result.':'.$customerIdentifier;
        $expectedHash = base64_encode(hash('sha256', $hashData, true));

        if (!hash_equals($expectedHash, $hash ?? '')) {
            throw new InvalidTdsDataException('Hash verification failed.');
        }

        return $callbackData;
    }
}
