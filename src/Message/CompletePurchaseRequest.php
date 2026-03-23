<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\PaymentInstrument;

class CompletePurchaseRequest extends AbstractLidioRequest
{
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

    public function getData(): array
    {
        $instrument = $this->getPaymentInstrument() ?? PaymentInstrument::NewCard->value;

        $data = [
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
            'systemTransId' => $this->getTransactionReference(),
            'totalAmount' => (float) $this->getAmount(),
            'currency' => $this->getCurrency(),
            'paymentInstrument' => $instrument,
            'clientIp' => $this->getClientIp(),
            'clientPort' => $this->getClientPort() ?? '',
            'clientType' => $this->getClientType() ?? 'Web',
        ];

        if ($this->getPaymentInstrumentInfo()) {
            $data['paymentInstrumentInfo'] = $this->getPaymentInstrumentInfo();
        } else {
            // Default: empty object for the selected instrument
            $data['paymentInstrumentInfo'] = [
                $instrument => new \stdClass(),
            ];
        }

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

        if ($this->getCustomParameters()) {
            $data['customParameters'] = $this->getCustomParameters();
        }

        return $data;
    }

    protected function getEndpoint(): string
    {
        return 'FinishPaymentProcess';
    }

    protected function getResponseClass(): string
    {
        return CompletePurchaseResponse::class;
    }
}
