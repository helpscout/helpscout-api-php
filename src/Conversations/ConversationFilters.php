<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use DateTime;
use DateTimeZone;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Reports\Report;

class ConversationFilters
{
    /**
     * @var int
     */
    private $mailbox;

    /**
     * @var int
     */
    private $folderId;

    /**
     * @var string
     */
    private $status;

    /**
     * @var array
     */
    private $tag;

    /**
     * @var int
     */
    private $assignedTo;

    /**
     * @var int
     */
    private $number;

    /**
     * @var DateTime
     */
    private $modifiedSince;

    /**
     * @var string
     */
    private $sortField;

    /**
     * @var string
     */
    private $sortOrder;

    /**
     * @var array
     */
    private $customFieldIds;

    /**
     * @var string
     */
    private $query;

    public function getParams(): array
    {
        $params = [
            'mailbox' => $this->mailbox,
            'folder' => $this->folderId,
            'status' => $this->status,
            'assigned_to' => $this->assignedTo,
            'number' => $this->number,
            'modifiedSince' => $this->modifiedSince !== null ? $this->modifiedSince->format(Report::DATE_FORMAT) : null,
            'sortField' => $this->sortField,
            'sortOrder' => $this->sortOrder,
            'query' => $this->query,
        ];

        if (\is_array($this->tag)) {
            $params['tag'] = implode(',', $this->tag);
        }

        if (\is_array($this->customFieldIds)) {
            $params['customFieldsByIds'] = implode(',', $this->customFieldIds);
        }

        // Filter out null values
        return array_filter($params, function ($param) {
            return $param !== null;
        });
    }

    /**
     * @param mixed $value
     */
    public function withCustomFieldById(int $id, $value): ConversationFilters
    {
        $filters = clone $this;
        if ($this->customFieldIds === null) {
            $this->customFieldIds = [];
        }
        $filters->customFieldIds[] = "$id:$value";

        return $filters;
    }

    public function withCustomFieldsById(array $fields): ConversationFilters
    {
        $filters = clone $this;
        $filters->customFieldIds = $fields;

        return $filters;
    }

    /**
     * @return self
     */
    public function withMailbox(int $mailbox)
    {
        Assert::greaterThan($mailbox, 0);

        $filters = clone $this;
        $filters->mailbox = $mailbox;

        return $filters;
    }

    /**
     * @return self
     */
    public function withFolder(int $folderId)
    {
        $filters = clone $this;
        $filters->folderId = $folderId;

        return $filters;
    }

    /**
     * @return self
     */
    public function withStatus(string $status)
    {
        Assert::oneOf($status, [
            Status::ANY,
            Status::ACTIVE,
            Status::OPEN,
            Status::CLOSED,
            Status::PENDING,
            Status::SPAM,
        ]);

        $filters = clone $this;
        $filters->status = $status;

        return $filters;
    }

    /**
     * @param string $tag Either a tag name or slug
     *
     * @return self
     */
    public function withTag(string $tag)
    {
        $filters = clone $this;
        $filters->tag = [
            $tag,
        ];

        return $filters;
    }

    /**
     * @return self
     */
    public function withTags(array $tags)
    {
        $filters = clone $this;
        $filters->tag = $tags;

        return $filters;
    }

    /**
     * @return self
     */
    public function withAssignedTo(int $assigneeId)
    {
        $filters = clone $this;
        $filters->assignedTo = $assigneeId;

        return $filters;
    }

    /**
     * @return self
     */
    public function withModifiedSince(DateTime $modifiedSince)
    {
        $modifiedSince->setTimezone(new DateTimeZone('UTC'));

        $filters = clone $this;
        $filters->modifiedSince = $modifiedSince;

        return $filters;
    }

    /**
     * @return self
     */
    public function withNumber(int $number)
    {
        $filters = clone $this;
        $filters->number = $number;

        return $filters;
    }

    /**
     * @return self
     */
    public function withSortField(string $sortField)
    {
        Assert::oneOf($sortField, [
            'createdAt',
            'customerEmail',
            'mailboxid',
            'modifiedAt',
            'number',
            'score',
            'status',
            'subject',
        ]);

        $filters = clone $this;
        $filters->sortField = $sortField;

        return $filters;
    }

    /**
     * @return self
     */
    public function withSortOrder(string $sortOrder)
    {
        $sortOrder = strtolower($sortOrder);
        Assert::oneOf($sortOrder, [
            'asc',
            'desc',
        ]);

        $filters = clone $this;
        $filters->sortOrder = $sortOrder;

        return $filters;
    }

    /**
     * @see https://developer.helpscout.com/mailbox-api/endpoints/conversations/list/#query
     *
     * @return self
     */
    public function withQuery(string $query)
    {
        $filters = clone $this;
        $filters->query = $query;

        return $filters;
    }
}
