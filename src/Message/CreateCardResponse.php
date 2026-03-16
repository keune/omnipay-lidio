<?php

namespace Keune\OmnipayLidio\Message;

class CreateCardResponse extends AbstractLidioResponse
{
    public function getCardReference(): ?string
    {
        return $this->data['cardToken'] ?? null;
    }
}
