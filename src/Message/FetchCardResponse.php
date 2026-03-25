<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\Message\Model\Card;

class FetchCardResponse extends AbstractLidioResponse
{
    public function getCustomerId(): ?string
    {
        return $this->data['customerID'] ?? null;
    }

    public function getCard(): ?Card
    {
        $cardInfo = $this->data['cardInfo'] ?? null;

        if (null === $cardInfo) {
            return null;
        }

        return new Card($cardInfo);
    }
}
