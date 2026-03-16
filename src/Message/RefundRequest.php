<?php

namespace Keune\OmnipayLidio\Message;

class RefundRequest extends AbstractLidioRequest
{
    public function getRefundTransId(): ?string
    {
        return $this->getParameter('refundTransId');
    }

    public function setRefundTransId(string $value): self
    {
        return $this->setParameter('refundTransId', $value);
    }

    public function getData(): array
    {
        return [
            'refundTransId' => $this->getRefundTransId(),
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
            'totalAmount' => (float) $this->getAmount(),
            'currency' => $this->getCurrency(),
            'clientIp' => $this->getClientIp(),
        ];
    }

    protected function getEndpoint(): string
    {
        return 'Refund';
    }

    protected function getResponseClass(): string
    {
        return RefundResponse::class;
    }
}
