<?php

namespace Keune\OmnipayLidio\Message;

class GetBankOfBinNumberRequest extends AbstractLidioRequest
{
    protected function getEndpoint(): string
    {
        return 'GetBankOfBINNumber';
    }

    protected function getResponseClass(): string
    {
        return GetBankOfBinNumberResponse::class;
    }

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

        if ($this->getClientType() !== null) {
            $data['clientType'] = $this->getClientType();
        }

        if ($this->getClientIp() !== null) {
            $data['clientIp'] = $this->getClientIp();
        }

        if ($this->getClientPort() !== null) {
            $data['clientPort'] = (int) $this->getClientPort();
        }

        if ($this->getClientUserAgent() !== null) {
            $data['clientUserAgent'] = $this->getClientUserAgent();
        }

        if ($this->getClientInfo() !== null) {
            $data['clientInfo'] = $this->getClientInfo();
        }

        return $data;
    }
}