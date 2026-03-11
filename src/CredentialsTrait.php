<?php

namespace Keune\OmnipayLidio;

trait CredentialsTrait
{
    public function getMerchantCode(): ?string
    {
        return $this->getParameter('merchantCode');
    }

    public function setMerchantCode(string $value): self
    {
        return $this->setParameter('merchantCode', $value);
    }

    public function getAuthorizationToken(): ?string
    {
        return $this->getParameter('authorizationToken');
    }

    public function setAuthorizationToken(string $value): self
    {
        return $this->setParameter('authorizationToken', $value);
    }

    public function getMerchantKey(): ?string
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey(string $value): self
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getApiPassword(): ?string
    {
        return $this->getParameter('apiPassword');
    }

    public function setApiPassword(string $value): self
    {
        return $this->setParameter('apiPassword', $value);
    }
}
