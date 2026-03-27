<?php

namespace Keune\OmnipayLidio\Message\Model;

class CompletePurchaseResultCategory extends AbstractModel
{
    public function getCategoryCode(): ?string
    {
        return $this->get('categoryCode');
    }

    public function getCategoryName(): ?string
    {
        return $this->get('categoryName');
    }

    public function getRecommendedUIMessageTR(): ?string
    {
        return $this->get('recommendedUIMessageTR');
    }

    public function getRecommendedUIMessageEN(): ?string
    {
        return $this->get('recommendedUIMessageEN');
    }
}
