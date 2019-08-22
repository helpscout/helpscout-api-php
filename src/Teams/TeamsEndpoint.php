<?php

declare(strict_types=1);

namespace HelpScout\Api\Teams;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;

class TeamsEndpoint extends Endpoint
{
    public const LIST_USERS_URI = '/v2/teams';
    public const RESOURCE_KEY = 'teams';

    /**
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
}
