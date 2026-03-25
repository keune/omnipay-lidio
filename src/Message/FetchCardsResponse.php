<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\Message\Model\Card;

class FetchCardsResponse extends AbstractLidioResponse
{
    /**
     * @return null|Card[]
     */
    public function getCards(): ?array
    {
        $cardList = $this->data['cardList'] ?? null;

        if (null === $cardList) {
            return null;
        }

        return array_map(static fn (array $card) => new Card($card), $cardList);
    }
}
