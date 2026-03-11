<?php

namespace Keune\OmnipayLidio\Message;

use Omnipay\Common\Message\AbstractResponse;

class AbstractLidioResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['result']) && $this->data['result'] === 'Success';
    }

    public function getMessage(): ?string
    {
        return $this->data['resultMessage'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->data['result'] ?? null;
    }

    public function getResultDetail(): ?string
    {
        return $this->data['resultDetail'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->data['paymentInfo']['systemTransId']
            ?? $this->data['systemTransId']
            ?? null;
    }

    public function getTransactionId(): ?string
    {
        return $this->data['paymentInfo']['orderId']
            ?? $this->data['orderId']
            ?? null;
    }
}
