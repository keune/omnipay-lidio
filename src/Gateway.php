<?php

namespace Keune\OmnipayLidio;

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
}
