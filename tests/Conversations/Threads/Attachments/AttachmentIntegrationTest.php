<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads\Attachments;

use HelpScout\Api\Conversations\Threads\Attachments\Attachment;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\AttachmentPayloads;

/**
 * @group integration
 */
class AttachmentIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testDeleteAttachment()
    {
        $this->stubResponse($this->getResponse(204));
        $this->client->attachments()->delete(1, 12);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1/attachments/12',
            'DELETE'
        );
    }

    public function testCreateAttachment()
    {
        $attachment = new Attachment();
        $attachment->setMimeType('image/jpeg');
        $attachment->setData('ZmlsZQ==');

        $this->stubResponse($this->getResponse(204));
        $this->client->attachments()->create(1, 12, $attachment);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1/threads/12/attachments',
            'POST'
        );
    }

    public function testGetAttachmentData()
    {
        $this->stubResponse($this->getResponse(200, AttachmentPayloads::getAttachmentData(12)));

        $conversation = $this->client->attachments()->get(1, 12);

        $this->assertInstanceOf(Attachment::class, $conversation);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/conversations/1/attachments/12/data'
        );
    }
}
