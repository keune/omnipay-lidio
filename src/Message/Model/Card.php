<?php

namespace Keune\OmnipayLidio\Message\Model;

class Card extends AbstractModel
{
    public function isDefault(): bool
    {
        return $this->get('isDefault', false);
    }

    public function getCardHolderName(): ?string
    {
        return $this->get('cardHolderName');
    }

    public function getMaskedCardNumber(): ?string
    {
        return $this->get('maskedCardNumber');
    }

    public function getCardToken(): ?string
    {
        return $this->get('cardToken');
    }

    public function isExpired(): ?bool
    {
        return $this->get('isExpired');
    }

    public function isConsentApproved(): ?bool
    {
        return $this->get('consentApproved');
    }

    public function isFinishPaymentRequired(): bool
    {
        return $this->get('finishPaymentRequired', false);
    }

    public function expiresIn90Days(): ?bool
    {
        return $this->get('expiresIn90Days');
    }

    public function getLastYear(): ?int
    {
        return $this->get('lastYear');
    }

    public function getLastMonth(): ?int
    {
        return $this->get('lastMonth');
    }

    public function getCardNameByUser(): ?string
    {
        return $this->get('cardNamebyUser');
    }

    public function getBankCode(): ?string
    {
        return $this->get('bankCode');
    }

    public function isDebitCard(): ?bool
    {
        return $this->get('isDebitCard');
    }

    public function getCardProgramName(): ?string
    {
        return $this->get('cardProgramName');
    }

    public function isBusinessCard(): ?bool
    {
        return $this->get('isBusinessCard');
    }

    public function getCardType(): ?string
    {
        return $this->get('cardType');
    }

    public function getBin(): ?string
    {
        return $this->get('bin');
    }

    public function getBinNumber(): ?string
    {
        return $this->get('binNumber');
    }

    public function isKkbVerified(): ?bool
    {
        return $this->get('kkbVerified');
    }

    public function getCardSavedDate(): ?string
    {
        return $this->get('cardSavedDate');
    }

    public function getMatchingPhoneTipList(): ?array
    {
        return $this->get('matchingPhoneTipList');
    }
}