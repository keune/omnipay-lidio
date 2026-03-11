<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\CredentialsTrait;
use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractLidioRequest extends AbstractRequest
{
    use CredentialsTrait;

    protected const BASE_URL_TEST = 'https://test.lidio.com/api';
    protected const BASE_URL_PROD = 'https://lidio.com/api';

    abstract protected function getEndpoint(): string;

    abstract protected function getResponseClass(): string;

    public function getBaseUrl(): string
    {
        return $this->getTestMode() ? self::BASE_URL_TEST : self::BASE_URL_PROD;
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'MerchantCode' => $this->getMerchantCode(),
            'Authorization' => $this->getAuthorizationToken(),
        ];
    }

    public function sendData($data): AbstractLidioResponse
    {
        $url = $this->getBaseUrl() . '/' . $this->getEndpoint();

        $httpResponse = $this->httpClient->request(
            'POST',
            $url,
            $this->getHeaders(),
            json_encode($data)
        );

        $responseData = json_decode((string) $httpResponse->getBody(), true);

        $responseClass = $this->getResponseClass();

        return $this->response = new $responseClass($this, $responseData);
    }

    // Common parameter accessors

    public function getOrderId(): ?string
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId(string $value): self
    {
        return $this->setParameter('orderId', $value);
    }

    public function getCustomerInfo(): ?array
    {
        return $this->getParameter('customerInfo');
    }

    public function setCustomerInfo(array $value): self
    {
        return $this->setParameter('customerInfo', $value);
    }

    public function getBasketItems(): ?array
    {
        return $this->getParameter('basketItems');
    }

    public function setBasketItems(array $value): self
    {
        return $this->setParameter('basketItems', $value);
    }

    public function getInvoiceAddress(): ?array
    {
        return $this->getParameter('invoiceAddress');
    }

    public function setInvoiceAddress(array $value): self
    {
        return $this->setParameter('invoiceAddress', $value);
    }

    public function getDeliveryAddress(): ?array
    {
        return $this->getParameter('deliveryAddress');
    }

    public function setDeliveryAddress(array $value): self
    {
        return $this->setParameter('deliveryAddress', $value);
    }

    public function getClientPort(): ?string
    {
        return $this->getParameter('clientPort');
    }

    public function setClientPort(string $value): self
    {
        return $this->setParameter('clientPort', $value);
    }

    public function getGroupCode(): ?string
    {
        return $this->getParameter('groupCode');
    }

    public function setGroupCode(string $value): self
    {
        return $this->setParameter('groupCode', $value);
    }

    public function getCustomParameters(): mixed
    {
        return $this->getParameter('customParameters');
    }

    public function setCustomParameters(mixed $value): self
    {
        return $this->setParameter('customParameters', $value);
    }
}
