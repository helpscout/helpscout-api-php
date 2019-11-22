<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Users;

use HelpScout\Api\Users\UserFilters;
use PHPUnit\Framework\TestCase;

class UserFiltersTest extends TestCase
{
    public function testGetParamsDoesNotReturnNullValues()
    {
        $filters = new UserFilters();

        $this->assertSame([], $filters->getParams());
    }

    public function testGetParams()
    {
        $filters = (new UserFilters())
            ->withMailbox(1)
            ->withEmail('tester@test.com');

        $this->assertSame([
            'mailbox' => 1,
            'email' => 'tester@test.com',
        ], $filters->getParams());
    }
}
