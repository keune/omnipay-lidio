<?php

namespace Keune\OmnipayLidio\Message\Model;

class PosResult extends AbstractModel
{
    public function getPosId(): ?int
    {
        return $this->get('posId');
    }

    public function getPosBankCode(): ?string
    {
        return $this->get('posBankCode');
    }

    public function getPosBankName(): ?string
    {
        return $this->get('posBankName');
    }

    public function getMdStatus(): ?string
    {
        return $this->get('mdStatus');
    }

    public function getReturnCode(): ?string
    {
        return $this->get('returnCode');
    }

    public function getMessage(): ?string
    {
        return $this->get('message');
    }

    public function getAuthCode(): ?string
    {
        return $this->get('authCode');
    }

    public function getPosTransId(): ?string
    {
        return $this->get('transId');
    }

    public function getReferenceNo(): ?string
    {
        return $this->get('referenceNo');
    }

    public function getRrn(): ?string
    {
        return $this->get('rrn');
    }

    public function getCustomData(): ?string
    {
        return $this->get('customData');
    }
}
