<?php

namespace Keune\OmnipayLidio\Message;

class FetchCardsRequest extends AbstractLidioRequest
{
    public function getCustomerId(): ?string
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId(string $value): self
    {
        return $this->setParameter('customerId', $value);
    }

    public function getPhone(): ?string
    {
        return $this->getParameter('phone');
    }

    public function setPhone(string $value): self
    {
        return $this->setParameter('phone', $value);
    }

    public function getEmail(): ?string
    {
        return $this->getParameter('email');
    }

    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
    }

    public function getData(): array
    {
        return [
            'customerId' => $this->getCustomerId(),
            'phone' => $this->getPhone(),
            'email' => $this->getEmail(),
            'clientIp' => $this->getClientIp() ?? '',
        ];
    }

    protected function getEndpoint(): string
    {
        return 'GetCardList';
    }

    protected function getResponseClass(): string
    {
        return FetchCardsResponse::class;
    }
}
