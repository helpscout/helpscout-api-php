<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Entity\Patch;
use HelpScout\Api\Exception\ValidationErrorException;
use HelpScout\Api\Http\Hal\HalPagedResources;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Tags\TagsCollection;

class ConversationsEndpoint extends Endpoint
{
    public function get(int $id, ConversationRequest $conversationRequest = null): Conversation
    {
        $conversationResource = $this->restClient->getResource(Conversation::class, sprintf('/v2/conversations/%d', $id));

        return $this->hydrateConversationWithSubEntities($conversationResource, $conversationRequest ?: new ConversationRequest());
    }

    public function delete(int $conversationId): void
    {
        $this->restClient->deleteResource(sprintf('/v2/conversations/%d', $conversationId));
    }

    /**
     * @throws ValidationErrorException
     */
    public function create(Conversation $conversation): ?int
    {
        return $this->restClient->createResource($conversation, sprintf('/v2/conversations'));
    }

    /**
     * Updates the custom field values for a given conversation.  Ommitted fields are removed.
     *
     * @param CustomField[]|array|Collection|CustomFieldsCollection $customFields
     *
     * @throws ValidationErrorException
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
     * @param array|Collection|TagsCollection $tags
     *
     * @throws ValidationErrorException
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
     */
    public function move(int $conversationId, int $toMailboxId): void
    {
        $patch = Patch::move('mailboxId', $toMailboxId);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * Update the subject of a conversation.
     *
     * @throws ValidationErrorException
     */
    public function updateSubject(int $conversationId, string $subject): void
    {
        $patch = Patch::replace('subject', $subject);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * Change the customer associated with a conversation.
     *
     * @throws ValidationErrorException
     */
    public function updateCustomer(int $conversationId, int $newCustomerId): void
    {
        $patch = Patch::replace('primaryCustomer.id', $newCustomerId);
        $this->patchConversation($conversationId, $patch);
    }

    public function publishDraft(int $conversationId): void
    {
        $patch = Patch::replace('draft', true);
        $this->patchConversation($conversationId, $patch);
    }

    /**
     * @throws ValidationErrorException
     */
    public function updateStatus(int $conversationId, string $status): void
    {
        $patch = Patch::replace('status', $status);
        $this->patchConversation($conversationId, $patch);
    }

    public function assign(int $conversationId, int $assigneeId): void
    {
        $patch = Patch::replace('assignTo', $assigneeId);
        $this->patchConversation($conversationId, $patch);
    }

    public function unassign(int $conversationId): void
    {
        $patch = Patch::remove('assignTo');
        $this->patchConversation($conversationId, $patch);
    }

    private function patchConversation(int $conversationId, Patch $patch): void
    {
        $this->restClient->patchResource($patch, sprintf('/v2/conversations/%d', $conversationId));
    }
}
