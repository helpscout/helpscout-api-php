<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads\Attachments;

use HelpScout\Api\Endpoint;

class AttachmentsEndpoint extends Endpoint
{
    /**
     * @param int $conversationId
     * @param int $attachmentId
     *
     * @return Attachment
     */
    public function get(int $conversationId, int $attachmentId): Attachment
    {
        $conversationResource = $this->restClient->getResource(
            Attachment::class,
            sprintf('/v2/conversations/%d/attachments/%d/data', $conversationId, $attachmentId)
        );

        return $conversationResource->getEntity();
    }

    /**
     * @param int        $conversationId
     * @param int        $threadId
     * @param Attachment $attachment
     *
     * @return int|null
     */
    public function create(int $conversationId, int $threadId, Attachment $attachment): ?int
    {
        return $this->restClient->createResource(
            $attachment,
            sprintf('/v2/conversations/%d/threads/%d/attachments', $conversationId, $threadId)
        );
    }

    /**
     * @param int $conversationId
     * @param int $attachmentId
     */
    public function delete(int $conversationId, int $attachmentId): void
    {
        $this->restClient->deleteResource(
            sprintf('/v2/conversations/%d/attachments/%d', $conversationId, $attachmentId)
        );
    }
}
