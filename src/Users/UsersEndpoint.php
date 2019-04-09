<?php

declare(strict_types=1);

namespace HelpScout\Api\Users;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;

class UsersEndpoint extends Endpoint
{
    public const GET_USER_URI = '/v2/users/%d';
    public const AUTH_USER_URI = '/v2/users/me';
    public const LIST_USERS_URI = '/v2/users';
    public const RESOURCE_KEY = 'users';

    public function get(int $id): User
    {
        return $this->loadResource(
            User::class,
            sprintf(self::GET_USER_URI, $id)
        );
    }

    public function getAuthenticatedUser(): User
    {
        return $this->loadResource(
            User::class,
            self::AUTH_USER_URI
        );
    }

    /**
     * @return User[]|PagedCollection
     */
    public function list(): PagedCollection
    {
        return $this->loadPage(
            User::class,
            self::RESOURCE_KEY,
            self::LIST_USERS_URI
        );
    }
}
