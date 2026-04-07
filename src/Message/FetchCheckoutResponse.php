<?php

namespace Omnipay\Polar\Message;

use Omnipay\Common\Message\AbstractResponse;
use Polar\Models\Components\CheckoutStatus;

class FetchCheckoutResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->data !== null
            && $this->data->status === CheckoutStatus::Succeeded;
    }

    public function getTransactionReference()
    {
        return $this->data->id ?? null;
    }

    public function getStatus()
    {
        return $this->data->status->value ?? null;
    }

    public function getAmount()
    {
        return $this->data->totalAmount ?? null;
    }
}
