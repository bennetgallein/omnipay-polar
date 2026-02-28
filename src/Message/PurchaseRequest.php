<?php

namespace Omnipay\Polar\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Polar\Models\Components\CheckoutCreate;
use Polar\Models\Components\PresentmentCurrency;
use Polar\Models\Components\ProductPriceFixedCreate;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'productId');

        $productId = $this->getProductId();
        $currency = $this->getCurrency()
            ? PresentmentCurrency::from(strtolower($this->getCurrency()))
            : null;

        $price = new ProductPriceFixedCreate(
            priceAmount: $this->getAmountInteger(),
            priceCurrency: $currency,
        );

        $params = [
            'products' => [$productId],
            'prices' => [$productId => [$price]],
        ];

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
