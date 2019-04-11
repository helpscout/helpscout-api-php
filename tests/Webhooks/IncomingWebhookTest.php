<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Webhooks;

use GuzzleHttp\Psr7\Request;
use HelpScout\Api\Conversations\Conversation;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Exception\InvalidSignatureException;
use HelpScout\Api\Tests\Payloads\ConversationPayloads;
use HelpScout\Api\Tests\Payloads\CustomerPayloads;
use HelpScout\Api\Webhooks\IncomingWebhook;
use PHPUnit\Framework\TestCase;

class IncomingWebhookTest extends TestCase
{
    protected function createRequest(array $headers = [], string $body)
    {
        return new Request('POST', 'www.blah.blah', $headers, $body);
    }

    protected function generateSignature(string $body, string $secret): string
    {
        return base64_encode(
            hash_hmac(
                'sha1',
                $body,
                $secret,
                true
            )
        );
    }

    public function testMakesWebhookFromGlobals()
    {
        $secret = uniqid();
        $body = @file_get_contents('php://input');
        $signature = $this->generateSignature($body, $secret);
        $_SERVER['REQUEST_METHOD'] = uniqid();
        $_SERVER['REQUEST_URI'] = uniqid();
        $_SERVER['HTTP_X_HELPSCOUT_SIGNATURE'] = $signature;

        $webhook = IncomingWebhook::makeFromGlobals($secret);
        $request = $webhook->getRequest();

        $this->assertSame(strtoupper($_SERVER['REQUEST_METHOD']), $request->getMethod());
        $this->assertSame($_SERVER['REQUEST_URI'], (string) $request->getUri());
        $this->assertSame([
            $signature,
        ], $request->getHeader('HTTP_X_HELPSCOUT_SIGNATURE'));
        $this->assertSame('', $request->getBody()->getContents());
    }

    public function testCreateIncomingConvoWebhook()
    {
        $body = ConversationPayloads::getConversation(123);
        $secret = 'asdffdsa';

        $signature = $this->generateSignature($body, $secret);

        $headers = [
            'HTTP_X_HELPSCOUT_SIGNATURE' => $signature,
            'HTTP_X_HELPSCOUT_EVENT' => 'convo',
        ];

        $webhook = new IncomingWebhook(
            $this->createRequest($headers, $body),
            $secret
        );

        $this->assertTrue($webhook->isConversationEvent());
        $convo = $webhook->getConversation();
        $this->assertInstanceOf(
            Conversation::class,
            $convo
        );
        $this->assertSame(123, $convo->getId());
    }

    public function testCreateIncomingCustomerWebhook()
    {
        $body = CustomerPayloads::getCustomer(123);
        $secret = 'asdffdsa';

        $signature = $this->generateSignature($body, $secret);

        $headers = [
            'HTTP_X_HELPSCOUT_SIGNATURE' => $signature,
            'HTTP_X_HELPSCOUT_EVENT' => 'customer',
        ];

        $webhook = new IncomingWebhook(
            $this->createRequest($headers, $body),
            $secret
        );

        $this->assertTrue($webhook->isCustomerEvent());
        $customer = $webhook->getCustomer();
        $this->assertInstanceOf(
            Customer::class,
            $customer
        );
        $this->assertSame(123, $customer->getId());
    }

    public function testCreateIncomingWebhook()
    {
        $body = json_encode(['id' => 123]);
        $secret = 'asdffdsa';

        $signature = $this->generateSignature($body, $secret);

        $headers = [
            'HTTP_X_HELPSCOUT_SIGNATURE' => $signature,
            'HTTP_X_HELPSCOUT_EVENT' => 'helpscout.test',
        ];

        $webhook = new IncomingWebhook(
            $this->createRequest($headers, $body),
            $secret
        );

        $this->assertTrue($webhook->isTestEvent());
        $this->assertSame(
            ['id' => 123],
            $webhook->getDataArray()
        );
        $obj = $webhook->getDataObject();
        $this->assertSame(123, $obj->id);
    }

    public function testCreateInvalidIncomingWebhook()
    {
        $this->expectException(InvalidSignatureException::class);

        $body = json_encode(['id' => 123]);
        $secret = 'asdffdsa';

        $headers = [
            'X_HELPSCOUT_SIGNATURE' => 'asdfasdfasdfasdf',
            'X_HELPSCOUT_EVENT' => 'helpscout.test',
        ];

        $webhook = new IncomingWebhook(
            $this->createRequest($headers, $body),
            $secret
        );
    }
}
