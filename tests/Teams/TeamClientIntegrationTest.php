<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Teams;

use HelpScout\Api\Teams\Team;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\TeamPayloads;
use HelpScout\Api\Tests\Payloads\UserPayloads;
use HelpScout\Api\Users\User;

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

    public function testGetTeamMembers()
    {
        $this->stubResponse(
            $this->getResponse(200, UserPayloads::getUsers(1, 10))
        );

        $teamId = 123;
        $users = $this->client->teams()->members($teamId);

        $this->assertCount(10, $users);
        $this->assertInstanceOf(User::class, $users[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/teams/'.$teamId.'/members'
        );
    }
}
