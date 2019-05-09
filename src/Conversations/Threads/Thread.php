<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use DateTimeInterface;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Conversations\Threads\Attachments\Attachment;
use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Exception\RuntimeException;
use HelpScout\Api\Support\ExtractsData;
use HelpScout\Api\Support\HydratesData;

class Thread implements Extractable, Hydratable
{
    use HydratesData,
        ExtractsData,
        HasPartiesToBeNotified,
        IncludesThreadDetails;

    /**
     * @var int
     */
    private $id;

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
    private $actionType;

    /**
     * @var string|null
     */
    private $actionText;

    /**
     * @var string|null
     */
    private $text;

    /**
     * @var array|null
     */
    private $assignedTo;

    /**
     * @var int|null
     */
    private $savedReplyId;

    /**
     * @var array|null
     */
    private $to = [];

    /**
     * @var DateTimeInterface|null
     */
    private $openedAt;

    /**
     * @var Collection
     */
    private $attachments;

    /**
     * @var bool
     */
    private $imported = false;

    public function __construct()
    {
        $this->attachments = new Collection();
    }

    public static function resourceUrl(int $conversationId): string
    {
        throw new RuntimeException('Unrecognized thread type');
    }

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }

        $this->status = $data['status'] ?? null;
        $this->state = $data['state'] ?? null;

        if (isset($data['text'])) {
            $this->text = $data['text'];
        } elseif (isset($data['body'])) {
            $this->text = $data['body'];
        }

        if (isset($data['action'])) {
            $this->actionType = $data['action']['type'] ?? null;
            $this->actionText = $data['action']['text'] ?? null;
        }

        if (isset($data['source']) && is_array($data['source'])) {
            $this->hydrateSource($data['source']);
        }

        if (isset($data['createdBy'])) {
            $this->hydrateCreatedBy($data['createdBy']);
        }

        $this->assignedTo = $data['assignedTo'] ?? null;
        $this->savedReplyId = $data['savedReplyId'] ?? null;

        if (isset($data['to'])) {
            if (is_array($data['to'])) {
                $this->to = $data['to'];
            } else {
                $this->to = [
                    (string) $data['to'],
                ];
            }
        }

        if (isset($data['cc'])) {
            $this->hydrateCC($data['cc']);
        }

        if (isset($data['bcc'])) {
            $this->hydrateBCC($data['bcc']);
        }

        $this->createdAt = $this->transformDateTime($data['createdAt'] ?? null);
        $this->openedAt = $this->transformDateTime($data['openedAt'] ?? null);

        if (isset($embedded['attachments'])) {
            $attachments = [];
            foreach ($embedded['attachments'] as $attachmentData) {
                $attachment = new Attachment();
                $attachment->hydrate($attachmentData);
                $attachments[] = $attachment;
            }
            $this->setAttachments(new Collection($attachments));
        }

        if (isset($data['imported']) && $data['imported']) {
            $this->setImported(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $data = [
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'state' => $this->getState(),
            'action' => null,
            'text' => $this->getText(),
            'source' => [
                'type' => $this->getSourceType(),
                'via' => $this->getSourceVia(),
            ],
            'assignedTo' => $this->getAssignedTo(),
            'savedReplyId' => $this->getSavedReplyId(),
        ];

        if ($this->wasCreatedByAction()) {
            $data['action'] = [
                'type' => $this->getActionType(),
                'text' => $this->getActionText(),
            ];
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

        $data['to'] = $this->getTo();
        $data['cc'] = $this->getCC();
        $data['bcc'] = $this->getBCC();

        if ($this->getCreatedAt() != null) {
            $data['createdAt'] = $this->to8601Utc($this->getCreatedAt());
        }

        if ($this->getOpenedAt() != null) {
            $data['openedAt'] = $this->to8601Utc($this->getOpenedAt());
        }

        if ($this->isImported()) {
            $data['imported'] = true;
        }

        if ($this->getAttachments()->count() > 0) {
            $attachments = [];
            foreach ($this->getAttachments() as $attachment) {
                $attachments[] = $attachment->extract();
            }
            $data['attachments'] = $attachments;
        }

        return $data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): Thread
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function wasCreatedByAction(): bool
    {
        return \is_string($this->actionText);
    }

    public function getActionType(): ?string
    {
        return $this->actionType;
    }

    public function getActionText(): ?string
    {
        return $this->actionText;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): Thread
    {
        $this->text = $text;

        return $this;
    }

    public function getAssignedTo(): ?array
    {
        return $this->assignedTo;
    }

    public function getTo(): ?array
    {
        return $this->to;
    }

    public function getSavedReplyId(): ?int
    {
        return $this->savedReplyId;
    }

    public function getOpenedAt(): ?DateTimeInterface
    {
        return $this->openedAt;
    }

    public function setAttachments(Collection $attachments): Thread
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function addAttachment(Attachment $attachment): Thread
    {
        $this->getAttachments()->append($attachment);

        return $this;
    }

    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function setImported(bool $imported): Thread
    {
        $this->imported = $imported;

        return $this;
    }

    public function isImported(): bool
    {
        return $this->imported;
    }
}
