<?php

namespace Omnipay\Polar\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Polar\Models\Components\CheckoutCreate;
use Polar\Models\Components\PresentmentCurrency;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'productId');

        $params = [
            'products' => [$this->getProductId()],
            'amount' => $this->getAmountInteger(),
        ];

        if ($this->getCurrency()) {
            $params['currency'] = PresentmentCurrency::from(strtolower($this->getCurrency()));
        }

        if ($this->getReturnUrl()) {
            $params['successUrl'] = $this->getReturnUrl();
        }

        $metadata = [];
        if ($this->getTransactionId()) {
            $metadata['transaction_id'] = $this->getTransactionId();
        }
        if ($this->getDescription()) {
            $metadata['description'] = $this->getDescription();
        }
        if (!empty($metadata)) {
            $params['metadata'] = $metadata;
        }

        if ($this->getCard()) {
            $card = $this->getCard();
            if ($card->getEmail()) {
                $params['customerEmail'] = $card->getEmail();
            }
            if ($card->getName()) {
                $params['customerName'] = $card->getName();
            }
        }

        return new CheckoutCreate(...$params);
    }

    public function sendData($data)
    {
        $sdk = $this->buildSdkClient();
        $response = $sdk->checkouts->create($data);

        return $this->response = new PurchaseResponse($this, $response->checkout);
    }
}
