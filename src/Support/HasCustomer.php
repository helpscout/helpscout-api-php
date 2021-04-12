<?php

declare(strict_types=1);

namespace HelpScout\Api\Support;

use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Customers\Entry\Email;

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

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    protected function hydrateCustomer(array $data)
    {
        $customer = new Customer();

        // A single email is returned with the customer resource
        if (isset($data['email'])) {
            $email = new Email();
            $email->setValue($data['email']);
            $customer->addEmail($email);

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
