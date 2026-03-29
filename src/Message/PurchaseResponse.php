<?php

namespace Keune\OmnipayLidio\Message;

use Keune\OmnipayLidio\Message\Model\Card;
use Keune\OmnipayLidio\Message\Model\PosResult;
use Keune\OmnipayLidio\Message\Model\PurchaseResultCategory;
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

    public function getPaymentInfo(): ?array
    {
        return $this->data['paymentInfo'] ?? null;
    }

    public function getPaymentInstrumentType(): ?string
    {
        return $this->data['paymentInfo']['instrumentType'] ?? null;
    }

    public function getPaymentCard(): ?Card
    {
        if (isset($this->data['paymentInfo']['instrumentDetail']['card']['maskedCardNumber'])) {
            return new Card($this->data['paymentInfo']['instrumentDetail']['card']);
        }

        return null;
    }

    public function getPaymentAcquirerType(): ?string
    {
        return $this->data['paymentInfo']['acquirerType'] ?? null;
    }

    public function getPaymentPosResult(): ?PosResult
    {
        if (isset($this->data['paymentInfo']['acquirerResultDetail']['pos']['posId'])) {
            return new PosResult($this->data['paymentInfo']['acquirerResultDetail']['pos']);
        }

        return null;
    }

    public function getPurchaseResultCategory(): ?PurchaseResultCategory
    {
        if (isset($this->data['paymentInfo']['resultCategory']['categoryCode'])) {
            return new PurchaseResultCategory($this->data['paymentInfo']['resultCategory']);
        }

        return null;
    }
}
