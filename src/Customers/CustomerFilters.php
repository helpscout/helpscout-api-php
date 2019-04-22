<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use DateTime;
use DateTimeZone;
use HelpScout\Api\Assert\Assert;

class CustomerFilters
{
    /**
     * @var int
     */
    private $mailbox;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

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
     * @var string
     */
    private $query;

    /**
     * @return array
     */
    public function getParams(): array
    {
        $params = [
            'mailbox' => $this->mailbox,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'modifiedSince' => $this->modifiedSince !== null ? $this->modifiedSince->format('c') : null,
            'sortField' => $this->sortField,
            'sortOrder' => $this->sortOrder,
            'query' => $this->query,
        ];

        // Filter out null values
        return array_filter($params, function ($param) {
            return $param !== null;
        });
    }

    /**
     * @param int $mailbox
     *
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
     * @param string $firstName
     *
     * @return self
     */
    public function withFirstName(string $firstName)
    {
        $filters = clone $this;
        $filters->firstName = $firstName;

        return $filters;
    }

    /**
     * @param string $lastName
     *
     * @return self
     */
    public function withLastName(string $lastName)
    {
        $filters = clone $this;
        $filters->lastName = $lastName;

        return $filters;
    }

    /**
     * @param DateTime $modifiedSince
     *
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
     * @param string $sortField
     *
     * @return self
     */
    public function withSortField(string $sortField)
    {
        Assert::oneOf($sortField, ['score', 'firstName', 'lastName', 'modifiedAt']);

        $filters = clone $this;
        $filters->sortField = $sortField;

        return $filters;
    }

    /**
     * @param string $sortOrder
     *
     * @return self
     */
    public function withSortOrder(string $sortOrder)
    {
        $sortOrder = strtolower($sortOrder);
        Assert::oneOf($sortOrder, ['asc', 'desc']);

        $filters = clone $this;
        $filters->sortOrder = $sortOrder;

        return $filters;
    }

    /**
     * @param string $query
     *
     * @see https://developer.helpscout.com/mailbox-api/endpoints/customers/list/#query
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
