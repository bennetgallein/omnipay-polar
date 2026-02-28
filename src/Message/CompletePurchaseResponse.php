<?php

namespace Omnipay\Polar\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->data['valid'] && $this->getEventType() === 'order.paid';
    }

    public function getTransactionReference()
    {
        return $this->data['payload']['data']['id'] ?? null;
    }

    public function getAmount()
    {
        return $this->data['payload']['data']['total_amount'] ?? null;
    }

    public function getMessage()
    {
        return $this->data['message'];
    }

    public function getEventType()
    {
        return $this->data['payload']['type'] ?? null;
    }

    public function getPayload()
    {
        return $this->data['payload'];
    }
}
