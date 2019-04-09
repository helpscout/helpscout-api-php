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
        $this->stubResponse(
            $this->getResponse(200, TagPayloads::getTags(1, 10))
        );

        $tags = $this->client->tags()->list();

        $this->assertCount(10, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/tags'
        );
    }

    public function testGetTagsLazyLoadsPages()
    {
        $this->stubResponses([
            $this->getResponse(200, TagPayloads::getTags(1, 20)),
            $this->getResponse(200, TagPayloads::getTags(2, 20)),
        ]);

        $tags = $this->client->tags()->list()->getPage(2);

        $this->assertCount(10, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/tags'],
            ['GET', 'https://api.helpscout.net/v2/tags?page=2'],
        ]);
    }
}
