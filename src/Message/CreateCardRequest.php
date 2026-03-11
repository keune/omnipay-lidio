<?php

namespace Keune\OmnipayLidio\Message;

class CreateCardRequest extends AbstractLidioRequest
{
    protected function getEndpoint(): string
    {
        return 'SaveCard';
    }

    protected function getResponseClass(): string
    {
        return CreateCardResponse::class;
    }

    public function getVerificationOtp(): ?string
    {
        return $this->getParameter('verificationOtp');
    }

    public function setVerificationOtp(string $value): self
    {
        return $this->setParameter('verificationOtp', $value);
    }

    public function getData(): array
    {
        $card = $this->getCard();

        $data = [
            'customerInfo' => $this->getCustomerInfo(),
            'cardHolderName' => $card ? $card->getName() : null,
            'cardNumber' => $card ? $card->getNumber() : null,
            'cardMonth' => $card ? (int) $card->getExpiryMonth() : null,
            'cardYear' => $card ? (int) $card->getExpiryYear() : null,
            'verificationOtp' => $this->getVerificationOtp(),
            'clientIP' => $this->getClientIp() ?? '',
        ];

        return $data;
    }
}
