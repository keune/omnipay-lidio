<?php

namespace Keune\OmnipayLidio\Message;

class FetchCardRequest extends AbstractLidioRequest
{
    public function getEmail(): ?string
    {
        return $this->getParameter('email');
    }

    public function setEmail(string $value): self
    {
        return $this->setParameter('email', $value);
    }

    public function getCustomerId(): ?string
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId(string $value): self
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCardToken(): ?string
    {
        return $this->getParameter('cardToken');
    }

    public function setCardToken(string $value): self
    {
        return $this->setParameter('cardToken', $value);
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
        $this->validate('cardToken');

        $data = [
            'cardToken' => $this->getCardToken(),
        ];

        if ($this->getEmail()) {
            $data['email'] = $this->getEmail();
        }

        if ($this->getCustomerId()) {
            $data['customerId'] = $this->getCustomerId();
        }

        if ($this->getClientType()) {
            $data['clientType'] = $this->getClientType();
        }

        if ($this->getClientIp()) {
            $data['clientIp'] = $this->getClientIp();
        }

        if ($this->getClientPort()) {
            $data['clientPort'] = (int) $this->getClientPort();
        }

        if ($this->getClientUserAgent()) {
            $data['clientUserAgent'] = $this->getClientUserAgent();
        }

        if ($this->getClientInfo()) {
            $data['clientInfo'] = $this->getClientInfo();
        }

        return $data;
    }

    protected function getEndpoint(): string
    {
        return 'TokenToCardInfoInquiry';
    }

    protected function getResponseClass(): string
    {
        return FetchCardResponse::class;
    }
}