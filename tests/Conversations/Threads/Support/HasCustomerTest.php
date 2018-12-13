<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations\Threads\Support;

use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Customers\Customer;
use PHPStan\Testing\TestCase;

class HasCustomerTest extends TestCase
{
    use HasCustomer;

    protected function tearDown()
    {
        parent::tearDown();

        $this->customer = null;
    }

    public function testCustomerDefaultsToNull()
    {
        $this->assertNull($this->getCustomer());
    }

    public function testCanSetCustomer()
    {
        $customer = new Customer();
        $customer->setId(4923);

        $this->setCustomer($customer);

        $this->assertEquals($customer, $this->getCustomer());
    }
}
