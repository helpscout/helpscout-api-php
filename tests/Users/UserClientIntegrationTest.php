<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Users;

use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\UserPayloads;
use HelpScout\Api\Users\User;

/**
 * @group integration
 */
class UserClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testGetUser()
    {
        $this->stubResponse(200, UserPayloads::getUser(1));

        $user = $this->client->users()->get(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(1, $user->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/users/1'
        );
    }

    public function testGetAuthenticatedUser()
    {
        $this->stubResponse(200, UserPayloads::getUser(1));

        $user = $this->client->users()->getAuthenticatedUser();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(1, $user->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/users/me'
        );
    }

    public function testGetUsers()
    {
        $this->stubUserResponse(200, 1, 10);

        $users = $this->client->users()->list();

        $this->assertCount(10, $users);
        $this->assertInstanceOf(User::class, $users[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/users'
        );
    }

    public function testGetUsersWithEmptyCollection()
    {
        $this->stubUserResponse(200, 1, 0);

        $users = $this->client->users()->list();

        $this->assertCount(0, $users);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/users'
        );
    }

    public function testGetUsersParsesPageMetadata()
    {
        $this->stubUserResponse(200, 3, 35);

        $users = $this->client->users()->list();

        $this->assertSame(3, $users->getPageNumber());
        $this->assertSame(10, $users->getPageSize());
        $this->assertSame(10, $users->getPageElementCount());
        $this->assertSame(35, $users->getTotalElementCount());
        $this->assertSame(4, $users->getTotalPageCount());
    }

    public function testGetUsersLazyLoadsPages()
    {
        $totalElements = 20;
        $this->stubUserResponse(200, 1, $totalElements);
        $this->stubUserResponse(200, 2, $totalElements);

        $users = $this->client->users()->list()->getPage(2);

        $this->assertCount(10, $users);
        $this->assertInstanceOf(User::class, $users[0]);

        $this->verifyMultpleRequests([
            ['GET', 'https://api.helpscout.net/v2/users'],
            ['GET', 'https://api.helpscout.net/v2/users?page=2'],
        ]);
    }

    protected function stubUserResponse(
        int $status,
        int $page,
        int $totalElements,
        array $headers = []
    ): void {
        $this->stubResponse(
            $status,
            UserPayloads::getUsers($page, $totalElements),
            $headers
        );
    }
}
