<?php

namespace Keune\OmnipayLidio\Message;

class GetInstallmentInfoResponse extends AbstractLidioResponse
{
    public function getPosList(): ?array
    {
        return $this->data['posList'] ?? null;
    }
}