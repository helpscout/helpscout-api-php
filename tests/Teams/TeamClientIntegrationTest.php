<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Users;

use HelpScout\Api\Teams\Team;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\TeamPayloads;

/**
 * @group integration
 */
class TeamClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testGetTeams()
    {
        $this->stubResponse(
            $this->getResponse(200, TeamPayloads::getTeams(1, 10))
        );

        $teams = $this->client->teams()->list();

        $this->assertCount(10, $teams);
        $this->assertInstanceOf(Team::class, $teams[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/teams'
        );
    }
}
