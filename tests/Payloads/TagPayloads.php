<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class TagPayloads
{
    /**
     * @return string
     */
    public static function getTag(): string
    {
        return json_encode(static::tag(1));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getTags(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $tags = array_map(function ($id) {
            return static::tag($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'tags' => $tags,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/tags',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/tags?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/tags?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/tags?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/tags{?page}',
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
    private static function tag(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'Dark Side',
            'slug' => 'dark-side',
            'color' => 'green',
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'ticketCount' => 5,
        ];
    }
}
