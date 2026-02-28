<?php

namespace Omnipay\Polar;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Polar';
    }

    public function getDefaultParameters()
    {
        return [
            'apiKey' => '',
            'sandbox' => false,
            'webhookSecret' => '',
        ];
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getSandbox()
    {
        return $this->getParameter('sandbox');
    }

    public function setSandbox($value)
    {
        return $this->setParameter('sandbox', $value);
    }

    public function getWebhookSecret()
    {
        return $this->getParameter('webhookSecret');
    }

    public function setWebhookSecret($value)
    {
        return $this->setParameter('webhookSecret', $value);
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Polar\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Polar\Message\CompletePurchaseRequest', $parameters);
    }
}
