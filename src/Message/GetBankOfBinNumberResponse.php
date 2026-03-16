<?php

namespace Keune\OmnipayLidio\Message;

class GetBankOfBinNumberResponse extends AbstractLidioResponse
{
    public function getBankCode(): ?string
    {
        return $this->data['bankCode'] ?? null;
    }

    public function getCardType(): ?string
    {
        return $this->data['cardType'] ?? null;
    }

    public function isDebitCard(): bool
    {
        return $this->data['isDebitCard'] ?? false;
    }

    public function getCardProgramName(): ?string
    {
        return $this->data['cardProgramName'] ?? null;
    }

    public function isBusinessCard(): bool
    {
        return $this->data['isBusinessCard'] ?? false;
    }
}
