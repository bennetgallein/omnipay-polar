<?php

namespace Omnipay\Polar\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class FetchCheckoutRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');
        return $this->getTransactionReference();
    }

    public function sendData($data)
    {
        $sdk = $this->buildSdkClient();
        $response = $sdk->checkouts->get($data);

        return $this->response = new FetchCheckoutResponse($this, $response->checkout);
    }
}
