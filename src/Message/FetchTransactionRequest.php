<?php

namespace Keune\OmnipayLidio\Message;

class FetchTransactionRequest extends AbstractLidioRequest
{
    public function getPaymentInstrument(): ?string
    {
        return $this->getParameter('paymentInstrument');
    }

    public function setPaymentInstrument(string $value): self
    {
        return $this->setParameter('paymentInstrument', $value);
    }

    public function getPaymentInquiryInstrumentInfo(): ?array
    {
        return $this->getParameter('paymentInquiryInstrumentInfo');
    }

    public function setPaymentInquiryInstrumentInfo(array $value): self
    {
        return $this->setParameter('paymentInquiryInstrumentInfo', $value);
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

    public function getData(): array
    {
        $data = [
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
            'totalAmount' => (float) $this->getAmount(),
            'paymentInstrument' => $this->getPaymentInstrument() ?? 'newCard',
        ];

        if ($this->getPaymentInquiryInstrumentInfo()) {
            $data['paymentInquiryInstrumentInfo'] = $this->getPaymentInquiryInstrumentInfo();
        } else {
            $data['paymentInquiryInstrumentInfo'] = [
                'card' => [
                    'processType' => $this->getProcessType(),
                    'posAccount' => [
                        'id' => $this->getPosAccountId() ?? 1,
                    ],
                ],
            ];
        }

        return $data;
    }

    protected function getEndpoint(): string
    {
        return 'PaymentInquiry';
    }

    protected function getResponseClass(): string
    {
        return FetchTransactionResponse::class;
    }
}
