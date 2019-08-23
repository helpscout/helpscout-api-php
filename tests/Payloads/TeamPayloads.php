<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class TeamPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getTeam(int $id): string
    {
        return json_encode(static::team($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getTeams(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $teams = array_map(function ($id) {
            return static::team($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'teams' => $teams,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/teams',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/teams?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/teams?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/teams?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/teams{?page}',
                    'templated' => true,
                ],
            ],
        ];

        if ($pageElements === 0) {
            // The _embedded key is not set when empty
            unset($data['_embedded']);
        }

        return json_encode($data);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    private static function team(int $id): array
    {
        return [
            'id' => $id,
            'createdAt' => '2019-08-23T14:39:56Z',
            'updatedAt' => '2019-08-23T14:43:24Z',
            'name' => 'Engineers',
            'timezone' => 'America/New_York',
            'photoUrl' => 'https://helpscout.com/images/avatar.jpg',
            'mention' => 'engs',
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/teams/$id",
                ],
            ],
        ];
    }
}
