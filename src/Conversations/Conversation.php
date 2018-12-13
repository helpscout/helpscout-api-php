<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use DateTimeInterface;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Conversations\Threads\IncludesThreadDetails;
use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;
use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Mailboxes\Mailbox;
use HelpScout\Api\Support\HydratesData;
use HelpScout\Api\Tags\Tag;
use HelpScout\Api\Users\User;

class Conversation implements Extractable, Hydratable
{
    use HydratesData,
        HasPartiesToBeNotified,
        IncludesThreadDetails,
        HasCustomer;

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

        $this->setThreadCount($data['threads'] ?? null);
        $this->setNumber($data['number'] ?? null);
        $this->setType($data['type'] ?? null);
        $this->setFolderId($data['folderId'] ?? null);
        $this->setStatus($data['status'] ?? null);
        $this->setState($data['state'] ?? null);
        $this->setSubject($data['subject'] ?? null);
        $this->setPreview($data['preview'] ?? null);
        $this->setMailboxId($data['mailboxId'] ?? null);

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
                $tag->hydrate($tagData);
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

        $customer = $this->getCustomer();
        if ($customer != null) {
            $data['customer'] = [
                'id' => $customer->getId(),
            ];

            $emails = $customer->getEmails()->toArray();
            if (isset($emails[0])) {
                $data['customer']['email'] = $emails[0];
            }
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
            $data['createdAt'] = $this->getCreatedAt()->format('c');
        }

        if ($this->getClosedAt() != null) {
            $data['closedAt'] = $this->getClosedAt()->format('c');
        }

        if ($this->getUserUpdatedAt() != null) {
            $data['userUpdatedAt'] = $this->getUserUpdatedAt()->format('c');
        }

        if ($this->wasCreatedByCustomer()) {
            $data['createdBy'] = [
                'id' => $this->getCreatedByCustomer()->getId(),
                'type' => 'customer',
            ];
        } elseif ($this->wasCreatedByUser()) {
            $data['createdBy'] = [
                'id' => $this->getCreatedByUser()->getId(),
                'type' => 'user',
            ];
        }

        if (count($this->getCustomFields()) > 0) {
            $customFields = [];
            foreach ($this->getCustomFields() as $customField) {
                $customFields[] = $customField->extract();
            }
            $data['fields'] = $customFields;
        }

        if (count($this->getThreads()) > 0) {
            $threads = [];
            foreach ($this->getThreads() as $thread) {
                $threads[] = $thread->extract();
            }
            $data['threads'] = $threads;
        }

        return $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;
    }

    public function getThreadCount(): ?int
    {
        return $this->threadCount;
    }

    public function setThreadCount(?int $threadCount)
    {
        $this->threadCount = $threadCount;
    }

    public function isAutoReplyEnabled(): bool
    {
        return $this->autoReplyEnabled;
    }

    public function withAutoRepliesEnabled()
    {
        $this->autoReplyEnabled = true;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number)
    {
        $this->number = $number;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type)
    {
        $this->type = $type;
    }

    public function setImported(bool $imported)
    {
        $this->imported = $imported;
    }

    public function isImported(): bool
    {
        return $this->imported;
    }

    public function getAssignTo(): ?int
    {
        return $this->assignTo;
    }

    public function setAssignTo(int $userId)
    {
        $this->assignTo = $userId;
    }

    public function getFolderId(): ?int
    {
        return $this->folderId;
    }

    public function setFolderId(?int $folderId)
    {
        $this->folderId = $folderId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status)
    {
        $this->status = $status;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state)
    {
        $this->state = $state;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject)
    {
        $this->subject = $subject;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview)
    {
        $this->preview = $preview;
    }

    public function getMailboxId(): ?int
    {
        return $this->mailboxId;
    }

    public function setMailboxId(?int $mailboxId)
    {
        $this->mailboxId = $mailboxId;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee)
    {
        $this->assignee = $assignee;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getClosedAt(): ?DateTimeInterface
    {
        return $this->closedAt;
    }

    public function setClosedAt(?DateTimeInterface $closedAt)
    {
        $this->closedAt = $closedAt;
    }

    public function getUserUpdatedAt(): ?DateTimeInterface
    {
        return $this->userUpdatedAt;
    }

    public function setUserUpdatedAt(?DateTimeInterface $userUpdatedAt)
    {
        $this->userUpdatedAt = $userUpdatedAt;
    }

    /**
     * Only available for conversations that have been closed.
     */
    public function getClosedBy(): ?User
    {
        return $this->closedBy;
    }

    public function setClosedBy(?User $closedBy)
    {
        $this->closedBy = $closedBy;
    }

    public function getCustomerWaitingSince(): ?CustomerWaitingSince
    {
        return $this->waitingSince;
    }

    public function setCustomerWaitingSince(?CustomerWaitingSince $waitingSince)
    {
        $this->waitingSince = $waitingSince;
    }

    /**
     * @return Tag[]|Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags)
    {
        $this->tags = $tags;
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
    public function setCustomFields(Collection $customFields)
    {
        $this->customFields = $customFields;
    }

    /**
     * @return Thread[]|Collection
     */
    public function getThreads(): Collection
    {
        return $this->threads;
    }

    /**
     * @param Thread[]|Collection $threads
     */
    public function setThreads(Collection $threads)
    {
        $this->threads = $threads;
    }

    public function setMailbox(Mailbox $mailbox)
    {
        $this->mailbox = $mailbox;
    }

    public function getMailbox(): ?Mailbox
    {
        return $this->mailbox;
    }
}
