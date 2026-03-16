<?php

namespace Keune\OmnipayLidio\Message;

class VoidRequest extends AbstractLidioRequest
{
    public function getData(): array
    {
        return [
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
        ];
    }

    protected function getEndpoint(): string
    {
        return 'Cancel';
    }

    protected function getResponseClass(): string
    {
        return VoidResponse::class;
    }
}
