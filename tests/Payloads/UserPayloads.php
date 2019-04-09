<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class UserPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getUser(int $id): string
    {
        return json_encode(static::user($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getUsers(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $users = array_map(function ($id) {
            return static::user($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'users' => $users,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/users',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/users?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/users?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/users?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/users{?page}',
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
    private static function user(int $id): array
    {
        return [
            'id' => $id,
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'firstName' => 'Big',
            'lastName' => 'Bird',
            'email' => 'bird@sesamestreet.com',
            'role' => 'owner',
            'timezone' => 'America/New_York',
            'photoUrl' => 'https://helpscout.com/images/avatar.jpg',
            'type' => 'user',
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/users/$id",
                ],
            ],
        ];
    }
}
