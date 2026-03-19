<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\Message\Model\Card;

class FetchCardsResponse extends AbstractLidioResponse
{
    /**
     * @return Card[]|null
     */
    public function getCards(): ?array
    {
        $cardList = $this->data['cardList'] ?? null;

        if ($cardList === null) {
            return null;
        }

        return array_map(static fn(array $card) => new Card($card), $cardList);
    }
}
