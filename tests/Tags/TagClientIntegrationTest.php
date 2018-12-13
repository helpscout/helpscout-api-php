<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Tags;

use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\TagPayloads;

/**
 * @group integration
 */
class TagClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testGetTags()
    {
        $this->stubTagResponse(200, 1, 10);

        $tags = $this->client->tags()->list();

        $this->assertCount(10, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/tags'
        );
    }

    public function testGetTagsLazyLoadsPages()
    {
        $totalElements = 20;

        $this->stubTagResponse(200, 1, $totalElements);
        $this->stubTagResponse(200, 2, $totalElements);

        $tags = $this->client->tags()->list()->getPage(2);

        $this->assertCount(10, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/tags'],
            ['GET', 'https://api.helpscout.net/v2/tags?page=2'],
        ]);
    }

    protected function stubTagResponse(
        int $status,
        int $page,
        int $totalElements,
        array $headers = []
    ): void {
        $this->stubResponse(
            $status,
            TagPayloads::getTags($page, $totalElements),
            $headers
        );
    }
}
