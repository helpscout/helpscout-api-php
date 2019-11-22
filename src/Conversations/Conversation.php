<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use DateTimeInterface;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Conversations\Threads\IncludesThreadDetails;
use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;
use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Conversations\Threads\ThreadFactory;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Mailboxes\Mailbox;
use HelpScout\Api\Support\ExtractsData;
use HelpScout\Api\Support\HydratesData;
use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Users\User;

class Conversation implements Extractable, Hydratable
{
    use HydratesData,
        ExtractsData,
        HasPartiesToBeNotified,
        IncludesThreadDetails,
        HasCustomer;

    public const TYPE_CHAT = 'chat';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PHONE = 'phone';

    public const STATUS_OPEN = 'open';
    public const STATUS_ALL = 'all';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SPAM = 'spam';

    public const STATE_DELETED = 'deleted';
    public const STATE_DRAFT = 'draft';
    public const STATE_PUBLISHED = 'published';

    /**
     * @var int
     */
    private $id;

    /**
     * @var int|null
     */
    private $threadCount;

    /**
     * @var bool
     */
    private $autoReplyEnabled = false;

    /**
     * @var int|null
     */
    private $number;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var bool
     */
    private $imported = false;

    /**
     * @var int|null
     */
    private $assignTo;

    /**
     * @var int
     */
    private $folderId;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var string|null
     */
    private $subject;

    /**
     * @var string|null
     */
    private $preview;

    /**
     * @var int|null
     */
    private $mailboxId;

    /**
     * @var User|null
     */
    private $assignee;

    /**
     * @var DateTimeInterface|null
     */
    private $closedAt;

    /**
     * @var User|null
     */
    private $closedBy;

    /**
     * @var DateTimeInterface|null
     */
    private $userUpdatedAt;

    /**
     * @var CustomerWaitingSince|null
     */
    private $waitingSince;

    /**
     * @var Collection
     */
    private $tags;

    /**
     * @var Customer
     */
    private $primaryCustomer;

    /**
     * @var Collection
     */
    private $customFields;

    /**
     * @var Collection
     */
    private $threads;

    /**
     * @var Mailbox
     */
    private $mailbox;

    public function __construct()
    {
        $this->tags = new Collection();
        $this->customFields = new Collection();
        $this->threads = new Collection();
    }

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }

        if (isset($data['threadCount'])) {
            $this->setThreadCount($data['threadCount'] ?? null);
        }

        if (isset($embedded['threads'])) {
            $this->hydrateThreads($embedded['threads']);
        }

        if (isset($data['threads'])) {
            // On some API calls these value is used to pass the thread count
            if (is_numeric($data['threads'])) {
                $this->setThreadCount($data['threads']);
            } elseif (is_array($data['threads'])) {
                $this->hydrateThreads($data['threads']);
            }
        }

        $this->setNumber($data['number'] ?? null);
        $this->setType($data['type'] ?? null);
        $this->setFolderId($data['folderId'] ?? null);
        $this->setStatus($data['status'] ?? null);
        $this->setState($data['state'] ?? null);
        $this->setSubject($data['subject'] ?? null);
        $this->setPreview($data['preview'] ?? null);
        $this->setMailboxId($data['mailboxId'] ?? null);

        // Webhook responses contain a full Mailbox object, not just the ID
        if (isset($data['mailbox'])) {
            $mailbox = new Mailbox();
            $mailbox->hydrate($data['mailbox']);
            $this->setMailbox($mailbox);
            // Sometimes in the API we only get the id, so we also have a getMailboxId().  To avoid confusion as to why that
            // method isn't returning the Mailbox id we'll also set that id here.
            $this->setMailboxId($mailbox->getId());
        }

        if (isset($data['assignee'])) {
            $assignee = new User();
            $assignee->hydrate($data['assignee']);
            $assignee->setFirstName($data['assignee']['first'] ?? null);
            $assignee->setLastName($data['assignee']['last'] ?? null);
            $this->setAssignee($assignee);
        }

        if (isset($data['createdBy'])) {
            $this->hydrateCreatedBy($data['createdBy']);
        }

        $this->setCreatedAt($this->transformDateTime($data['createdAt'] ?? null));
        $this->setClosedAt($this->transformDateTime($data['closedAt'] ?? null));
        $this->setUserUpdatedAt($this->transformDateTime($data['userUpdatedAt'] ?? null));

        if (isset($data['closedBy']) && $data['closedBy'] > 0) {
            $user = new User();
            $user->setId((int) $data['closedBy']);
            $this->setClosedBy($user);
        }

        if (isset($data['customerWaitingSince'])) {
            $waitingSince = new CustomerWaitingSince();
            $waitingSince->hydrate($data['customerWaitingSince']);
            $this->setCustomerWaitingSince($waitingSince);
        }

        if (isset($data['source']) && is_array($data['source'])) {
            $this->hydrateSource($data['source']);
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $this->tags = new Collection();
            foreach ($data['tags'] as $tagData) {
                $tag = new Tag();

                // Webhooks only return the tag name itself, not all the tag attributes
                if (is_array($tagData)) {
                    $tag->hydrate($tagData);
                } else {
                    $tag->setName($tagData);
                }
                $this->tags->append($tag);
            }
        }

        if (isset($data['cc'])) {
            $this->hydrateCC($data['cc']);
        }

        if (isset($data['bcc'])) {
            $this->hydrateBCC($data['bcc']);
        }

        if (isset($data['customer']) && is_array($data['customer'])) {
            $this->hydrateCustomer($data['customer']);
        }

        if (isset($data['primaryCustomer']) && is_array($data['primaryCustomer'])) {
            $this->hydrateCustomer($data['primaryCustomer']);
        }

        if (isset($data['customFields']) && is_array($data['customFields'])) {
            $this->customFields = new Collection();
            foreach ($data['customFields'] as $customFieldData) {
                $customField = new CustomField();
                $customField->hydrate($customFieldData);
                $this->customFields->append($customField);
            }
        }
    }

    protected function hydrateThreads(array $threads): void
    {
        $this->threads = new Collection();
        $threadFactory = new ThreadFactory();
        foreach ($threads as $threadData) {
            $thread = $threadFactory->make($threadData['type'], $threadData);
            $this->threads->append($thread);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $data = [
            'id' => $this->getId(),
            'number' => $this->getNumber(),
            'threadCount' => $this->getThreadCount(),
            'autoReply' => $this->isAutoReplyEnabled(),
            'type' => $this->getType(),
            'assignTo' => $this->getAssignTo(),
            'folderId' => $this->getFolderId(),
            'status' => $this->getStatus(),
            'state' => $this->getState(),
            'subject' => $this->getSubject(),
            'preview' => $this->getPreview(),
            'mailboxId' => $this->getMailboxId(),
            'assignee' => null,
            'createdAt' => $this->getCreatedAt(),
            'closedAt' => $this->getClosedAt(),
            'closedBy' => null,
            'userUpdatedAt' => $this->getUserUpdatedAt(),
            'cc' => $this->getCC(),
            'bcc' => $this->getBCC(),
        ];

        if ($this->getSourceType() != null || $this->getSourceVia() != null) {
            $data['source'] = [
                'type' => $this->getSourceType(),
                'via' => $this->getSourceVia(),
            ];
        }

        if ($this->isImported()) {
            $data['imported'] = true;
        }

        if ($this->hasCustomer()) {
            $data['customer'] = $this->getCustomer()->extract();
        }

        $assignee = $this->getAssignee();
        if ($assignee != null) {
            $data['assignee'] = [
                'id' => $assignee->getId(),
                'firstName' => $assignee->getFirstName(),
                'lastName' => $assignee->getLastName(),
            ];
        }

        $closedBy = $this->getClosedBy();
        if ($closedBy != null) {
            $data['closedBy'] = $closedBy->getId();
        }

        $customerWaitingSince = $this->getCustomerWaitingSince();
        if ($customerWaitingSince != null) {
            $data['customerWaitingSince'] = $customerWaitingSince->extract();
        }

        if (count($this->getTags()) > 0) {
            $tags = [];
            foreach ($this->getTags() as $tag) {
                $tags[] = $tag->getName();
            }
            $data['tags'] = $tags;
        }

        if ($this->getCreatedAt() != null) {
            $data['createdAt'] = $this->to8601Utc($this->getCreatedAt());
        }

        if ($this->getClosedAt() != null) {
            $data['closedAt'] = $this->to8601Utc($this->getClosedAt());
        }

        if ($this->getUserUpdatedAt() != null) {
            $data['userUpdatedAt'] = $this->to8601Utc($this->getUserUpdatedAt());
        }

        if ($this->wasCreatedByCustomer()) {
            $data['createdBy'] = [
                'id' => $this->getCreatedByCustomer()->getId(),
                'type' => 'customer',
            ];
        } elseif ($this->wasCreatedByUser()) {
            // Maintaining consistency with hydrate() so we don't incur data loss during the extract/hydrate chain
            $data['createdBy'] = [
                'id' => $this->getCreatedByUser()->getId(),
                'type' => 'user',
            ];
            // Api wants this to be the user id when creating conversations
            $data['user'] = $this->getCreatedByUser()->getId();
        }

        if (count($this->getCustomFields()) > 0) {
            $data['fields'] = $this->getCustomFields()->extract();
        }

        if (count($this->getThreads()) > 0) {
            $data['threads'] = $this->getThreads()->extract();
        }

        return $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Conversation
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getThreadCount(): ?int
    {
        return $this->threadCount;
    }

    public function setThreadCount(?int $threadCount): Conversation
    {
        $this->threadCount = $threadCount;

        return $this;
    }

    public function isAutoReplyEnabled(): bool
    {
        return $this->autoReplyEnabled;
    }

    public function withAutoRepliesEnabled(): Conversation
    {
        $this->autoReplyEnabled = true;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): Conversation
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Conversation
    {
        $this->type = $type;

        return $this;
    }

    public function isChatConvo(): bool
    {
        return $this->getType() === self::TYPE_CHAT;
    }

    public function isEmailConvo(): bool
    {
        return $this->getType() === self::TYPE_EMAIL;
    }

    public function isPhoneConvo(): bool
    {
        return $this->getType() === self::TYPE_PHONE;
    }

    public function setImported(bool $imported): Conversation
    {
        $this->imported = $imported;

        return $this;
    }

    public function isImported(): bool
    {
        return $this->imported;
    }

    public function getAssignTo(): ?int
    {
        return $this->assignTo;
    }

    public function setAssignTo(int $userId): Conversation
    {
        $this->assignTo = $userId;

        return $this;
    }

    public function getFolderId(): ?int
    {
        return $this->folderId;
    }

    public function setFolderId(?int $folderId): Conversation
    {
        $this->folderId = $folderId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): Conversation
    {
        $this->status = $status;

        return $this;
    }

    public function setClosed(): Conversation
    {
        return $this->setStatus(self::STATUS_CLOSED);
    }

    public function isClosed(): bool
    {
        return $this->getStatus() === self::STATUS_CLOSED;
    }

    public function setSpam(): Conversation
    {
        return $this->setStatus(self::STATUS_SPAM);
    }

    public function isSpam(): bool
    {
        return $this->getStatus() === self::STATUS_SPAM;
    }

    public function setPending(): Conversation
    {
        return $this->setStatus(self::STATUS_PENDING);
    }

    public function isPending(): bool
    {
        return $this->getStatus() === self::STATUS_PENDING;
    }

    public function setActive(): Conversation
    {
        return $this->setStatus(self::STATUS_ACTIVE);
    }

    public function isActive(): bool
    {
        return $this->getStatus() === self::STATUS_ACTIVE;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): Conversation
    {
        $this->state = $state;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->getState() === self::STATE_PUBLISHED;
    }

    public function publish(): Conversation
    {
        return $this->setState(self::STATE_PUBLISHED);
    }

    public function makeDraft(): Conversation
    {
        return $this->setState(self::STATE_DRAFT);
    }

    public function delete(): Conversation
    {
        return $this->setState(self::STATE_DELETED);
    }

    public function isDraft(): bool
    {
        return $this->getState() === self::STATE_DRAFT;
    }

    public function isDeleted(): bool
    {
        return $this->getState() === self::STATE_DELETED;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): Conversation
    {
        $this->subject = $subject;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): Conversation
    {
        $this->preview = $preview;

        return $this;
    }

    public function getMailboxId(): ?int
    {
        return $this->mailboxId;
    }

    public function setMailboxId(?int $mailboxId): Conversation
    {
        $this->mailboxId = $mailboxId;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function isAssigned(): bool
    {
        return $this->getAssignee() !== null;
    }

    public function setAssignee(?User $assignee): Conversation
    {
        $this->assignee = $assignee;
        $this->setAssignTo($assignee->getId());

        return $this;
    }

    public function assignTo(?User $assignee): Conversation
    {
        return $this->setAssignee($assignee);
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): Conversation
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClosedAt(): ?DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(?DateTimeInterface $closedAt): Conversation
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    public function getUserUpdatedAt(): ?DateTimeInterface
    {
        return $this->userUpdatedAt;
    }

    public function setUserUpdatedAt(?DateTimeInterface $userUpdatedAt): Conversation
    {
        $this->userUpdatedAt = $userUpdatedAt;

        return $this;
    }

    /**
     * Only available for conversations that have been closed.
     */
    public function getClosedBy(): ?User
    {
        return $this->closedBy;
    }

    public function setClosedBy(?User $closedBy): Conversation
    {
        $this->closedBy = $closedBy;

        return $this;
    }

    public function getCustomerWaitingSince(): ?CustomerWaitingSince
    {
        return $this->waitingSince;
    }

    public function setCustomerWaitingSince(?CustomerWaitingSince $waitingSince): Conversation
    {
        $this->waitingSince = $waitingSince;

        return $this;
    }

    /**
     * @return Tag[]|Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag[]|Collection $tags
     */
    public function setTags(Collection $tags): Conversation
    {
        $this->tags = $tags;

        return $this;
    }

    public function addTag(Tag $tag): Conversation
    {
        $this->getTags()->append($tag);

        return $this;
    }

    /**
     * @return CustomField[]|Collection
     */
    public function getCustomFields(): Collection
    {
        return $this->customFields;
    }

    /**
     * @param CustomField[]|Collection $customFields
     */
    public function setCustomFields(Collection $customFields): Conversation
    {
        $this->customFields = $customFields;

        return $this;
    }

    public function addCustomField(CustomField $customField): Conversation
    {
        $this->getCustomFields()->append($customField);

        return $this;
    }

    /**
     * Obtain the threads that were eagerly loaded when this conversation was obtained.
     *
     * We will attempt to map the incoming Thread to a typed class.  The only threads
     * we type are threads that can be created through the API (e.g. CustomerThread,
     * NoteThread, etc.).  We do not type any kind of system threads such as a notice
     * that a Workflow has run on a Conversation.
     *
     * @see ConversationRequest
     *
     * @return Thread[]|Collection
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    /**
     * @param Thread[]|Collection $threads
     */
    public function setThreads(Collection $threads): Conversation
    {
        $this->threads = $threads;

        return $this;
    }

    public function addThread(Thread $thread): Conversation
    {
        $this->getThreads()->append($thread);

        return $this;
    }

    public function setMailbox(Mailbox $mailbox): Conversation
    {
        $this->mailbox = $mailbox;

        return $this;
    }

    public function getMailbox(): ?Mailbox
    {
        return $this->mailbox;
    }
}
