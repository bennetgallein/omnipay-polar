<?php

namespace Omnipay\Polar\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $webhookSecret = $this->getWebhookSecret();
        if (empty($webhookSecret)) {
            throw new InvalidRequestException('The webhookSecret parameter is required');
        }

        $body = $this->httpRequest->getContent();
        $webhookId = $this->httpRequest->headers->get('webhook-id');
        $timestamp = $this->httpRequest->headers->get('webhook-timestamp');
        $signature = $this->httpRequest->headers->get('webhook-signature');

        if (empty($webhookId) || empty($timestamp) || empty($signature)) {
            return [
                'valid' => false,
                'payload' => null,
                'message' => 'Missing webhook headers',
            ];
        }

        $signedContent = "{$webhookId}.{$timestamp}.{$body}";

        $secret = $webhookSecret;
        if (str_starts_with($secret, 'whsec_')) {
            $secret = substr($secret, 6);
        }
        $secretBytes = base64_decode($secret);

        $computedSignature = base64_encode(
            hash_hmac('sha256', $signedContent, $secretBytes, true)
        );

        $signatures = explode(' ', $signature);
        $valid = false;
        foreach ($signatures as $sig) {
            $parts = explode(',', $sig, 2);
            $sigValue = $parts[1] ?? $parts[0];
            if (hash_equals($computedSignature, $sigValue)) {
                $valid = true;
                break;
            }
        }

        $payload = json_decode($body, true);

        return [
            'valid' => $valid,
            'payload' => $payload,
            'message' => $valid ? ($payload['type'] ?? 'unknown') : 'Invalid webhook signature',
        ];
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
