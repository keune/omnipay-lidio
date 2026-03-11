<?php

namespace Keune\OmnipayLidio\Message;

class VoidRequest extends AbstractLidioRequest
{
    protected function getEndpoint(): string
    {
        return 'Cancel';
    }

    protected function getResponseClass(): string
    {
        return VoidResponse::class;
    }

    public function getData(): array
    {
        return [
            'orderId' => $this->getTransactionId() ?? $this->getOrderId(),
        ];
    }
}
