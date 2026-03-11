<?php

namespace Keune\OmnipayLidio\Message;

class CaptureRequest extends AbstractLidioRequest
{
    protected function getEndpoint(): string
    {
        return 'PostAuth';
    }

    protected function getResponseClass(): string
    {
        return CaptureResponse::class;
    }

    public function getData(): array
    {
        return [
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
            'totalAmount' => (float) $this->getAmount(),
            'currency' => $this->getCurrency(),
            'clientIp' => $this->getClientIp(),
        ];
    }
}
