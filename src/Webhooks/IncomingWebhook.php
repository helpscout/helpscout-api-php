<?php

declare(strict_types=1);

namespace HelpScout\Api\Webhooks;

use GuzzleHttp\Psr7\Request;
use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Exception\InvalidSignatureException;
use HelpScout\Api\Exception\JsonException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use Psr\Http\Message\RequestInterface;

class IncomingWebhook
{
    public const SIGNATURE_HEADERS = [
        'HTTP_X_HELPSCOUT_SIGNATURE',
        'X_HELPSCOUT_SIGNATURE',
    ];

    public const EVENT_HEADERS = [
        'HTTP_X_HELPSCOUT_EVENT',
        'X_HELPSCOUT_EVENT',
    ];

    public const TEST_EVENT = 'helpscout.test';
    public const CONVO_EVENT = 'convo';
    public const CUSTOMER_EVENT = 'customer';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $secret;

    /**
     * IncomingWebhook constructor.
     *
     * @param RequestInterface $request
     * @param string           $secret
     */
    public function __construct(RequestInterface $request, string $secret)
    {
        $this->request = $request;
        $this->secret = $secret;

        $this->validateSignature();
    }

    public static function makeFromGlobals(string $secret): self
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (stripos($key, 'HTTP_') === 0) {
                $headers[$key] = $value;
            }
        }

        $request = new Request(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $headers,
            @file_get_contents('php://input')
        );

        return new IncomingWebhook($request, $secret);
    }

    /**
     * @throws InvalidSignatureException
     */
    protected function validateSignature(): void
    {
        $signature = $this->generateSignature();
        $header = $this->findHeader(self::SIGNATURE_HEADERS);

        if ($signature !== $header) {
            throw new InvalidSignatureException($signature, $header);
        }
    }

    /**
     * @return string
     */
    protected function generateSignature(): string
    {
        $body = $this->getJson();

        return base64_encode(
            hash_hmac(
                'sha1',
                $body,
                $this->secret,
                true
            )
        );
    }

    /**
     * @param array $headers
     *
     * @return string
     */
    protected function findHeader(array $headers): string
    {
        $signature = '';
        foreach ($headers as $header) {
            if ($this->request->hasHeader($header)) {
                $value = $this->request->getHeader($header);
                $value = array_shift($value);
                if ($value !== null) {
                    $signature = $value;
                    continue;
                }
            }
        }

        return $signature;
    }

    /**
     * @return bool
     */
    public function isTestEvent(): bool
    {
        return $this->findHeader(self::EVENT_HEADERS) === self::TEST_EVENT;
    }

    /**
     * @return bool
     */
    public function isConversationEvent(): bool
    {
        return $this->isEventTypeOf('convo');
    }

    /**
     * @return bool
     */
    public function isCustomerEvent(): bool
    {
        return $this->isEventTypeOf('customer');
    }

    /**
     * @param string $eventType
     *
     * @return bool
     */
    protected function isEventTypeOf(string $eventType): bool
    {
        $header = $this->getEventType();

        return strpos($header, $eventType) === 0;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->findHeader(self::EVENT_HEADERS);
    }

    /**
     * @return array
     */
    public function getDataArray(): array
    {
        return \json_decode(
            $this->getJson(),
            true
        );
    }

    /**
     * @return \stdClass
     */
    public function getDataObject(): \stdClass
    {
        return \json_decode(
            $this->getJson()
        );
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return (string) $this->request->getBody();
    }

    /**
     * @return Conversation
     *
     * @throws JsonException
     */
    public function getConversation(): Conversation
    {
        $body = $this->getJson();

        $resource = HalDeserializer::deserializeResource(
            Conversation::class,
            HalDeserializer::deserializeDocument($body)
        );

        return $resource->getEntity();
    }

    /**
     * @return Customer
     *
     * @throws JsonException
     */
    public function getCustomer(): Customer
    {
        $body = $this->getJson();

        $resource = HalDeserializer::deserializeResource(
            Customer::class,
            HalDeserializer::deserializeDocument($body)
        );

        return $resource->getEntity();
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
