<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\PaymentInstrument;
use Omnipay\Common\CreditCard;

class PurchaseRequest extends AbstractLidioRequest
{
    // Purchase-specific parameters

    public function getPaymentInstrument(): ?string
    {
        return $this->getParameter('paymentInstrument');
    }

    public function setPaymentInstrument(string $value): self
    {
        return $this->setParameter('paymentInstrument', $value);
    }

    public function getPaymentInstrumentInfo(): ?array
    {
        return $this->getParameter('paymentInstrumentInfo');
    }

    public function setPaymentInstrumentInfo(array $value): self
    {
        return $this->setParameter('paymentInstrumentInfo', $value);
    }

    public function getUse3DSecure(): bool
    {
        return (bool) $this->getParameter('use3DSecure');
    }

    public function setUse3DSecure(bool $value): self
    {
        return $this->setParameter('use3DSecure', $value);
    }

    public function getSaveAfterSuccess(): bool
    {
        return (bool) $this->getParameter('saveAfterSuccess');
    }

    public function setSaveAfterSuccess(bool $value): self
    {
        return $this->setParameter('saveAfterSuccess', $value);
    }

    public function getInstallmentCount(): int
    {
        return (int) $this->getParameter('installmentCount');
    }

    public function setInstallmentCount(int $value): self
    {
        return $this->setParameter('installmentCount', $value);
    }

    public function getExtraInstallment(): int
    {
        return (int) $this->getParameter('extraInstallment');
    }

    public function setExtraInstallment(int $value): self
    {
        return $this->setParameter('extraInstallment', $value);
    }

    public function getPosAccountId(): ?int
    {
        return $this->getParameter('posAccountId');
    }

    public function setPosAccountId(int $value): self
    {
        return $this->setParameter('posAccountId', $value);
    }

    public function getProcessType(): string
    {
        return $this->getParameter('processType') ?? 'sales';
    }

    public function setProcessType(string $value): self
    {
        return $this->setParameter('processType', $value);
    }

    public function getMerchantProcessId(): ?string
    {
        return $this->getParameter('merchantProcessId');
    }

    public function setMerchantProcessId(string $value): self
    {
        return $this->setParameter('merchantProcessId', $value);
    }

    public function getMerchantCustomField(): ?string
    {
        return $this->getParameter('merchantCustomField');
    }

    public function setMerchantCustomField(string $value): self
    {
        return $this->setParameter('merchantCustomField', $value);
    }

    public function getClientType(): ?string
    {
        return $this->getParameter('clientType');
    }

    public function setClientType(string $value): self
    {
        return $this->setParameter('clientType', $value);
    }

    public function getClientUserAgent(): ?string
    {
        return $this->getParameter('clientUserAgent');
    }

    public function setClientUserAgent(string $value): self
    {
        return $this->setParameter('clientUserAgent', $value);
    }

    public function getClientInfo(): ?string
    {
        return $this->getParameter('clientInfo');
    }

    public function setClientInfo(string $value): self
    {
        return $this->setParameter('clientInfo', $value);
    }

    public function getAlternateNotificationUrl(): ?string
    {
        return $this->getParameter('alternateNotificationUrl');
    }

    public function setAlternateNotificationUrl(string $value): self
    {
        return $this->setParameter('alternateNotificationUrl', $value);
    }

    public function getData(): array
    {
        $this->validate('amount', 'currency');

        $data = [
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
            'totalAmount' => (float) $this->getAmount(),
            'currency' => $this->getCurrency(),
            'customerInfo' => $this->getCustomerInfo() ?? new \stdClass(),
            'clientIp' => $this->getClientIp(),
            'clientPort' => $this->getClientPort() ?? '',
            'clientType' => $this->getClientType() ?? 'Web',
        ];

        if ($this->getMerchantProcessId()) {
            $data['merchantProcessId'] = $this->getMerchantProcessId();
        }

        if ($this->getMerchantCustomField()) {
            $data['merchantCustomField'] = $this->getMerchantCustomField();
        }

        if ($this->getClientUserAgent()) {
            $data['clientUserAgent'] = $this->getClientUserAgent();
        }

        if ($this->getClientInfo()) {
            $data['clientInfo'] = $this->getClientInfo();
        }

        // Build paymentInstrument and paymentInstrumentInfo
        if ($this->getPaymentInstrumentInfo()) {
            // Raw paymentInstrumentInfo passed directly
            $data['paymentInstrument'] = $this->getPaymentInstrument() ?? 'newCard';
            $data['paymentInstrumentInfo'] = $this->getPaymentInstrumentInfo();
        } elseif ($this->getToken()) {
            // Stored card via token
            $data['paymentInstrument'] = 'storedCard';
            $data['paymentInstrumentInfo'] = [
                'storedCard' => [
                    'processType' => $this->getProcessType(),
                    'cardToken' => $this->getToken(),
                    'use3DSecure' => $this->getUse3DSecure(),
                    'installmentCount' => $this->getInstallmentCount(),
                    'extraInstallment' => $this->getExtraInstallment(),
                    'amountDetail' => [
                        'baseAmount' => 0,
                        'interestAmount' => 0,
                    ],
                    'loyaltyPointUsage' => 'none',
                    'loyaltyPointAmount' => 0,
                    'posAccount' => [
                        'id' => $this->getPosAccountId() ?? 0,
                    ],
                ],
            ];
        } else {
            // New card from Omnipay CreditCard
            $card = $this->getCard();
            if ($card) {
                $card->validate();
            }

            $data['paymentInstrument'] = PaymentInstrument::NewCard->value;
            $data['paymentInstrumentInfo'] = [
                'newCard' => [
                    'processType' => $this->getProcessType(),
                    'cardInfo' => [
                        'cardHolderName' => $card ? $card->getName() : null,
                        'cardNumber' => $card ? $card->getNumber() : null,
                        'lastMonth' => $card ? (int) $card->getExpiryMonth() : null,
                        'lastYear' => $card ? (int) $card->getExpiryYear() : null,
                    ],
                    'saveAfterSuccess' => $this->getSaveAfterSuccess(),
                    'use3DSecure' => $this->getUse3DSecure(),
                    'cvv' => $card ? $card->getCvv() : null,
                    'installmentCount' => $this->getInstallmentCount(),
                    'extraInstallment' => $this->getExtraInstallment(),
                    'amountDetail' => [
                        'baseAmount' => 0,
                        'interestAmount' => 0,
                    ],
                    'loyaltyPointUsage' => 'none',
                    'loyaltyPointAmount' => 0,
                    'posAccount' => [
                        'id' => $this->getPosAccountId() ?? 1,
                    ],
                ],
            ];
        }

        if ($this->getBasketItems()) {
            $data['basketItems'] = $this->getBasketItems();
        }

        if ($this->getInvoiceAddress()) {
            $data['invoiceAddress'] = $this->getInvoiceAddress();
        }

        if ($this->getDeliveryAddress()) {
            $data['deliveryAddress'] = $this->getDeliveryAddress();
        }

        $data['returnUrl'] = $this->getReturnUrl() ?? '';
        $data['notificationUrl'] = $this->getNotifyUrl() ?? '';
        $data['groupCode'] = $this->getGroupCode() ?? '';
        $data['customParameters'] = $this->getCustomParameters() ?? '';

        if ($this->getAlternateNotificationUrl()) {
            $data['alternateNotificationUrl'] = $this->getAlternateNotificationUrl();
        }

        return $data;
    }

    protected function getEndpoint(): string
    {
        return 'ProcessPayment';
    }

    protected function getResponseClass(): string
    {
        return PurchaseResponse::class;
    }
}
