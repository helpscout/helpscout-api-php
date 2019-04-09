<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use DateTimeInterface;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Users\User;

/**
 * The API includes some thread details in both the `Conversation` and `Thread` responses.  To avoid duplicating code
 * we store these common attributes in this trait.
 */
trait IncludesThreadDetails
{
    /**
     * @var string|null
     */
    private $sourceType;

    /**
     * @var string|null
     */
    private $sourceVia;

    /**
     * @var DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @var User
     */
    private $createdByUser;

    /**
     * @var Customer
     */
    private $createdByCustomer;

    public function getSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function setSourceType(?string $sourceType)
    {
        $this->sourceType = $sourceType;
    }

    public function getSourceVia(): ?string
    {
        return $this->sourceVia;
    }

    public function setSourceVia(?string $sourceVia)
    {
        $this->sourceVia = $sourceVia;
    }

    public function wasCreatedByUser(): bool
    {
        return $this->createdByUser instanceof User;
    }

    public function setCreatedByUser(User $user)
    {
        $this->createdByUser = $user;
    }

    public function getCreatedByUser(): User
    {
        return $this->createdByUser;
    }

    public function wasCreatedByCustomer(): bool
    {
        return $this->createdByCustomer instanceof Customer;
    }

    public function setCreatedByCustomer(Customer $customer)
    {
        $this->createdByCustomer = $customer;
    }

    public function getCreatedByCustomer(): Customer
    {
        return $this->createdByCustomer;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    protected function hydrateSource(array $input)
    {
        $this->setSourceType($input['type'] ?? null);
        $this->setSourceVia($input['via'] ?? null);
    }

    protected function hydrateCreatedBy(array $data)
    {
        if (isset($data['type'])) {
            if ($data['type'] == 'customer') {
                $customer = new Customer();
                $customer->hydrate($data);
                $this->setCreatedByCustomer($customer);
            } elseif ($data['type'] == 'user') {
                $user = new User();
                $user->hydrate($data);
                $this->setCreatedByUser($user);
            }
        }
    }
}
