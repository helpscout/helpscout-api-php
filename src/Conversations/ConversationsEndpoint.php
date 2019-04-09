<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Entity\Patch;
use HelpScout\Api\Http\Hal\HalPagedResources;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Tags\TagsCollection;

class ConversationsEndpoint extends Endpoint
{
    /**
     * @param int                      $id
     * @param ConversationRequest|null $conversationRequest
     *
     * @return Conversation
     */
    public function get(int $id, ConversationRequest $conversationRequest = null): Conversation
    {
        $conversationResource = $this->restClient->getResource(Conversation::class, sprintf('/v2/conversations/%d', $id));

        return $this->hydrateConversationWithSubEntities($conversationResource, $conversationRequest ?: new ConversationRequest());
    }

    /**
     * @param int $conversationId
     */
    public function delete(int $conversationId): void
    {
        $this->restClient->deleteResource(sprintf('/v2/conversations/%d', $conversationId));
    }

    /**
     * @param Conversation $conversation
     *
     * @return int|null
     */
    public function create(Conversation $conversation): ?int
    {
        return $this->restClient->createResource($conversation, sprintf('/v2/conversations'));
    }

    /**
     * Updates the custom field values for a given conversation.  Ommitted fields are removed.
     *
     * @param int                                                   $conversationId
     * @param CustomField[]|array|Collection|CustomFieldsCollection $customFields
     */
    public function updateCustomFields(int $conversationId, $customFields): void
    {
        if ($customFields instanceof CustomFieldsCollection) {
            $customFieldsCollection = $customFields;
        } else {
            if ($customFields instanceof Collection) {
                $customFields = $customFields->toArray();
            }

            $customFieldsCollection = new CustomFieldsCollection();
            $customFieldsCollection->setCustomFields($customFields);
        }

        $this->restClient->updateResource(
            $customFieldsCollection,
            sprintf('/v2/conversations/%d/fields', $conversationId)
        );
    }

    /**
     * Updates the tags for a given conversation.
     * Omitted tags are removed.
     *
     * @param int                             $conversationId
     * @param array|Collection|TagsCollection $tags
     */
    public function updateTags(int $conversationId, $tags): void
    {
        if ($tags instanceof TagsCollection) {
            $tagsCollection = $tags;
        } else {
            if ($tags instanceof Collection) {
                $tagNames = [];
                foreach ($tags as $tag) {
                    $tagNames[] = $tag->getName();
                }
                $tags = $tagNames;
            }

            $tagsCollection = new TagsCollection();
            $tagsCollection->setTags($tags);
        }

        $this->restClient->updateResource(
            $tagsCollection,
            sprintf('/v2/conversations/%d/tags', $conversationId)
        );
    }

    /**
     * @param ConversationFilters|null $conversationFilters
     * @param ConversationRequest|null $conversationRequest
     *
     * @return Conversation[]|PagedCollection
     */
    public function list(
        ConversationFilters $conversationFilters = null,
        ConversationRequest $conversationRequest = null
    ): PagedCollection {
        $uri = '/v2/conversations';
        if ($conversationFilters) {
            $params = $conversationFilters->getParams();
            if (!empty($params)) {
                $uri .= '?'.http_build_query($params);
            }
        }

        return $this->loadConversations(
            $uri,
            $conversationRequest ?: new ConversationRequest()
        );
    }

    /**
     * @param string              $uri
     * @param ConversationRequest $conversationRequest
     *
     * @return Conversation[]|PagedCollection
     */
    private function loadConversations(string $uri, ConversationRequest $conversationRequest): PagedCollection
    {
        /** @var HalPagedResources $conversationResources */
        $conversationResources = $this->restClient->getResources(Conversation::class, 'conversations', $uri);
        $conversations = $conversationResources->map(function (HalResource $customerResource) use ($conversationRequest) {
            return $this->hydrateConversationWithSubEntities($customerResource, $conversationRequest);
        });

        return new PagedCollection(
            $conversations,
            $conversationResources->getPageMetadata(),
            $conversationResources->getLinks(),
            function (string $uri) use ($conversationRequest) {
                return $this->loadConversations($uri, $conversationRequest);
            }
        );
    }

    /**
     * @param HalResource         $conversationResource
     * @param ConversationRequest $conversationRequest
     *
     * @return Conversation
     */
    private function hydrateConversationWithSubEntities(
        HalResource $conversationResource,
        ConversationRequest $conversationRequest
    ): Conversation {
        $conversationLoader = new ConversationLoader($this->restClient, $conversationResource, $conversationRequest->getLinks());
        $conversationLoader->load();

        return $conversationResource->getEntity();
    }

    /**
     * Move a conversation to a given mailbox.
     *
     * @param int $conversationId
     * @param int $toMailboxId
     */
    public function move(int $conversationId, int $toMailboxId): void
    {
        $patch = Patch::move('mailboxId', $toMailboxId);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * Update the subject of a conversation.
     *
     * @param int    $conversationId
     * @param string $subject
     */
    public function updateSubject(int $conversationId, string $subject): void
    {
        $patch = Patch::replace('subject', $subject);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * Change the customer associated with a conversation.
     *
     * @param int $conversationId
     * @param int $newCustomerId
     */
    public function updateCustomer(int $conversationId, int $newCustomerId): void
    {
        $patch = Patch::replace('primaryCustomer.id', $newCustomerId);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * @param int $conversationId
     */
    public function publishDraft(int $conversationId): void
    {
        $patch = Patch::replace('draft', true);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * @param int    $conversationId
     * @param string $status
     */
    public function updateStatus(int $conversationId, string $status): void
    {
        $patch = Patch::replace('status', $status);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * @param int $conversationId
     * @param int $assigneeId
     */
    public function assign(int $conversationId, int $assigneeId): void
    {
        $patch = Patch::replace('assignTo', $assigneeId);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * @param int $conversationId
     */
    public function unassign(int $conversationId): void
    {
        $patch = Patch::remove('assignTo');
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * @param int   $conversationId
     * @param Patch $patch
     */
    private function patchConversation(int $conversationId, Patch $patch): void
    {
        $this->restClient->patchResource($patch, sprintf('/v2/conversations/%d', $conversationId));
    }
}
