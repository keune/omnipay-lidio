<?php

namespace Keune\OmnipayLidio\Message;

class GetInstallmentInfoRequest extends AbstractLidioRequest
{
    protected function getEndpoint(): string
    {
        return 'GetInstallmentInfo';
    }

    protected function getResponseClass(): string
    {
        return GetInstallmentInfoResponse::class;
    }

    public function getBin(): ?string
    {
        return $this->getParameter('bin');
    }

    public function setBin(string $value): self
    {
        return $this->setParameter('bin', $value);
    }

    public function getPosId(): ?int
    {
        return $this->getParameter('posId');
    }

    public function setPosId(int $value): self
    {
        return $this->setParameter('posId', $value);
    }

    public function getCardCategory(): string
    {
        return $this->getParameter('cardCategory') ?? 'AllCards';
    }

    public function setCardCategory(string $value): self
    {
        return $this->setParameter('cardCategory', $value);
    }

    public function getData(): array
    {
        return [
            'BIN' => $this->getBin(),
            'amount' => (float) $this->getAmount(),
            'posId' => $this->getPosId() ?? 0,
            'cardCategory' => $this->getCardCategory(),
        ];
    }
}
