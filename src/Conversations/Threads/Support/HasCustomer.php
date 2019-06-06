<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads\Support;

use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\Email;
use HelpScout\Api\Entity\Collection;

trait HasCustomer
{
    /**
     * @var Customer|null
     */
    private $customer;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    protected function hydrateCustomer(array $data)
    {
        $customer = new Customer();

        // For a Conversation the API returns a single email address along
        // with a Customer.
        if (isset($data['email'])) {
            $email = new Email();
            $email->setValue($data['email']);
            $emails = new Collection([$email]);
            $customer->setEmails($emails);
            unset($data['email']);
        }

        $customer->hydrate($data);

        $this->setCustomer($customer);
    }

    protected function hasCustomer(): bool
    {
        return $this->getCustomer() instanceof Customer;
    }
}
