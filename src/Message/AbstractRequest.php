<?php

namespace Omnipay\Polar\Message;

use Polar\Polar;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getProductId()
    {
        return $this->getParameter('productId');
    }

    public function setProductId($value)
    {
        return $this->setParameter('productId', $value);
    }

    public function getWebhookSecret()
    {
        return $this->getParameter('webhookSecret');
    }

    public function setWebhookSecret($value)
    {
        return $this->setParameter('webhookSecret', $value);
    }

    public function getSandbox()
    {
        return $this->getParameter('sandbox');
    }

    public function setSandbox($value)
    {
        return $this->setParameter('sandbox', $value);
    }

    protected function buildSdkClient(): Polar
    {
        $builder = Polar::builder()
            ->setSecurity($this->getApiKey());

        if ($this->getSandbox()) {
            $builder->setServer(Polar::SERVER_SANDBOX);
        } else {
            $builder->setServer(Polar::SERVER_PRODUCTION);
        }

        return $builder->build();
    }
}
