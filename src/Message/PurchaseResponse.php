<?php

namespace Keune\OmnipayLidio\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractLidioResponse implements RedirectResponseInterface
{
    public function isRedirect(): bool
    {
        return 'RedirectFormCreated' === $this->data['result']
            || 'VerificationRequired' === $this->data['result'];
    }

    public function isRedirectFormCreated(): bool
    {
        return 'RedirectFormCreated' === $this->data['result'];
    }

    public function isVerificationRequired(): bool
    {
        return ($this->data['result'] ?? null) === 'VerificationRequired';
    }

    public function getRedirectUrl(): ?string
    {
        return $this->data['redirectFormParams']['actionLink'] ?? null;
    }

    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    public function getRedirectData(): ?array
    {
        $paramList = $this->data['redirectFormParams']['paramList'] ?? null;

        if (!is_array($paramList)) {
            return null;
        }

        $data = [];
        foreach ($paramList as $param) {
            if (isset($param['name'])) {
                $data[$param['name']] = $param['value'] ?? '';
            }
        }

        return $data;
    }

    public function getRedirectForm(): ?string
    {
        return $this->data['redirectForm'] ?? null;
    }

    public function getSystemTransId(): ?string
    {
        return $this->getTransactionReference();
    }
}
