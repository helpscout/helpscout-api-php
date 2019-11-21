<?php

declare(strict_types=1);

namespace HelpScout\Api\Teams;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Users\User;
use HelpScout\Api\Users\UsersEndpoint;

class TeamsEndpoint extends Endpoint
{
    public const LIST_USERS_URI = '/v2/teams';
    public const RESOURCE_KEY = 'teams';

    /**
     * Get a list of teams.
     *
     * @return Team[]|PagedCollection
     */
    public function list(): PagedCollection
    {
        return $this->loadPage(
            Team::class,
            self::RESOURCE_KEY,
            self::LIST_USERS_URI
        );
    }

    /**
     * Get the members of a team.
     *
     * @return User[]|PagedCollection
     */
    public function members(int $teamId): PagedCollection
    {
        return $this->loadPage(
            User::class,
            UsersEndpoint::RESOURCE_KEY,
            sprintf(self::LIST_USERS_URI.'/%d/members', $teamId)
        );
    }
}
