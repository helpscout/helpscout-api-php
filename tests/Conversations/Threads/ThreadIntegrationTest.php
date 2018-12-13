<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\CustomerThread;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\ThreadPayloads;

/**
 * @group integration
 */
class ThreadIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testCreateThread()
    {
        $this->stubResponse(204);
        $thread = new CustomerThread();

        $this->client->threads()->create(14, $thread);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/14/customer',
            'POST'
        );
    }

    public function testGetThreads()
    {
        $this->stubResponse(200, ThreadPayloads::getThreads(1, 10));

        $conversations = $this->client->threads()->list(14);

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(CustomerThread::class, $conversations[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/14/threads'
        );
    }

    public function testGetThreadsParsesPageMetadata()
    {
        $this->stubResponse(200, ThreadPayloads::getThreads(3, 35));

        $threads = $this->client->threads()->list(1);

        $this->assertSame(3, $threads->getPageNumber());
        $this->assertSame(10, $threads->getPageSize());
        $this->assertSame(10, $threads->getPageElementCount());
        $this->assertSame(35, $threads->getTotalElementCount());
        $this->assertSame(4, $threads->getTotalPageCount());
    }

    public function testGetThreadsWithEmptyCollection()
    {
        $this->stubResponse(200, ThreadPayloads::getThreads(1, 0));

        $conversations = $this->client->threads()->list(1);

        $this->assertCount(0, $conversations);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1/threads'
        );
    }

    public function testGetThreadsLazyLoadsPages()
    {
        $totalElements = 20;
        $this->stubResponse(200, ThreadPayloads::getThreads(1, $totalElements));
        $this->stubResponse(200, ThreadPayloads::getThreads(2, $totalElements));

        $conversations = $this->client->threads()->list(1)->getPage(2);

        $this->assertCount(10, $conversations);
        $this->assertInstanceOf(CustomerThread::class, $conversations[0]);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/conversations/1/threads'],
            ['GET', 'https://api.helpscout.net/v2/conversations/1/threads?page=2'],
        ]);
    }

    public function testCanUpdateThreadText()
    {
        $this->client->threads()->updateText(1, 1432, 'This is new text');

        $this->verifyRequestWithData('https://api.helpscout.net/v2/conversations/1/threads/1432', 'PATCH', [
            'op' => 'replace',
            'path' => '/text',
            'value' => 'This is new text',
        ]);
    }
}
