<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\Message\Model\Card;
use Keune\OmnipayLidio\Message\Model\PosResult;

class CompletePurchaseResponse extends AbstractLidioResponse
{
    public function getPaymentInfo(): ?array
    {
        return $this->data['paymentInfo'] ?? null;
    }

    public function getPaymentInstrumentType(): ?string
    {
        return $this->data['paymentInfo']['instrumentType'] ?? null;
    }

    public function getPaymentCard(): ?Card
    {
        if (isset($this->data['paymentInfo']['instrumentDetail']['card']['maskedCardNumber'])) {
            return new Card($this->data['paymentInfo']['instrumentDetail']['card']);
        }

        return null;
    }

    public function getPaymentAcquirerType(): ?string
    {
        return $this->data['paymentInfo']['acquirerType'] ?? null;
    }

    public function getPaymentPosResult(): ?PosResult
    {
        if (isset($this->data['paymentInfo']['acquirerResultDetail']['pos']['posId'])) {
            return new PosResult($this->data['paymentInfo']['acquirerResultDetail']['pos']);
        }

        return null;
    }
}
