<?php

namespace Keune\OmnipayLidio\Message;

class FetchCardsResponse extends AbstractLidioResponse
{
    public function getCards(): ?array
    {
        return $this->data['cardList'] ?? null;
    }
}