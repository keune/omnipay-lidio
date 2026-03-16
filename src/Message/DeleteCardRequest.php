<?php

namespace Keune\OmnipayLidio\Message;

class DeleteCardRequest extends AbstractLidioRequest
{
    public function getCustomerId(): ?string
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId(string $value): self
    {
        return $this->setParameter('customerId', $value);
    }

    public function getData(): array
    {
        return [
            'cardToken' => $this->getCardReference(),
            'customerId' => $this->getCustomerId(),
            'clientIp' => $this->getClientIp() ?? '',
        ];
    }

    protected function getEndpoint(): string
    {
        return 'DeleteCard';
    }

    protected function getResponseClass(): string
    {
        return DeleteCardResponse::class;
    }
}
