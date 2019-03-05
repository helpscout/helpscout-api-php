<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Hal\Entity;

class StubPayloads
{
    public static function getResource(): string
    {
        return json_encode(self::resource(1));
    }

    public static function getResourceWithEmbeddedEntity(): string
    {
        $resource = self::resource(1);

        $resource['_embedded']['address'] = [
            'city' => 'Frankfurt',
        ];

        return json_encode($resource);
    }

    public static function getInvalidResource(): string
    {
        $resource = self::resource(1);
        unset($resource['_links']);

        return json_encode($resource);
    }

    public static function getResources(int $count): string
    {
        $entities = array_map(function ($id) {
            return static::resource($id);
        }, $count > 0 ? range(1, $count) : []);

        $data = [
            '_embedded' => [
                'entities' => $entities,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.com/entities',
                ],
            ],
        ];

        if (count($entities) === 0) {
            // The _embedded key is not set when empty
            unset($data['_embedded']);
        }

        return json_encode($data);
    }

    public static function getPagedResources(): string
    {
        $entities = array_map(function ($id) {
            return static::resource($id);
        }, range(1, 5));

        $data = [
            '_embedded' => [
                'entities' => $entities,
            ],
            'page' => [
                'size' => 5,
                'totalElements' => 25,
                'totalPages' => 5,
                'number' => 1,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.com/entities',
                ],
                'next' => [
                    'href' => 'https://api.com/entities?page=2',
                ],
                'first' => [
                    'href' => 'https://api.com/entities?page=1',
                ],
                'last' => [
                    'href' => 'https://api.com/entities?page=5',
                ],
                'page' => [
                    'href' => 'https://api.com/entities{?page}',
                    'templated' => true,
                ],
            ],
        ];

        if (empty($entities)) {
            // The _embedded key is not set when empty
            unset($data['_embedded']);
        }

        return json_encode($data);
    }

    private static function resource(int $id): array
    {
        return [
            'id' => $id,
            '_links' => [
                'self' => [
                    'href' => "https://api.com/entities/$id",
                ],
            ],
        ];
    }
}
