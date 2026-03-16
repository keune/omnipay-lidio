<?php

namespace Keune\OmnipayLidio\Message;

class GetBankOfBinNumberRequest extends AbstractLidioRequest
{
    public function getBin(): ?string
    {
        return $this->getParameter('bin');
    }

    public function setBin(string $value): self
    {
        return $this->setParameter('bin', $value);
    }

    public function getClientType(): ?string
    {
        return $this->getParameter('clientType');
    }

    public function setClientType(string $value): self
    {
        return $this->setParameter('clientType', $value);
    }

    public function getClientUserAgent(): ?string
    {
        return $this->getParameter('clientUserAgent');
    }

    public function setClientUserAgent(string $value): self
    {
        return $this->setParameter('clientUserAgent', $value);
    }

    public function getClientInfo(): ?string
    {
        return $this->getParameter('clientInfo');
    }

    public function setClientInfo(string $value): self
    {
        return $this->setParameter('clientInfo', $value);
    }

    public function getData(): array
    {
        $this->validate('bin');

        $data = [
            'bin' => $this->getBin(),
        ];

        if (null !== $this->getClientType()) {
            $data['clientType'] = $this->getClientType();
        }

        if (null !== $this->getClientIp()) {
            $data['clientIp'] = $this->getClientIp();
        }

        if (null !== $this->getClientPort()) {
            $data['clientPort'] = (int) $this->getClientPort();
        }

        if (null !== $this->getClientUserAgent()) {
            $data['clientUserAgent'] = $this->getClientUserAgent();
        }

        if (null !== $this->getClientInfo()) {
            $data['clientInfo'] = $this->getClientInfo();
        }

        return $data;
    }

    protected function getEndpoint(): string
    {
        return 'GetBankOfBINNumber';
    }

    protected function getResponseClass(): string
    {
        return GetBankOfBinNumberResponse::class;
    }
}
