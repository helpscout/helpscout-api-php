<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

use Ramsey\Uuid\Uuid;

class ChatPayloads
{
    public static function getChat(string $id): string
    {
        return json_encode(static::chat($id));
    }

    public static function getEvents(int $pageNumber, int $totalElements): string
    {
        $chatId = (string) Uuid::uuid4();

        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        $ids = [];
        for ($i = 0; $i < $pageElements; ++$i) {
            $ids[] = (string) Uuid::uuid4();
        }

        // Create embedded resources
        $events = array_map(function ($id) {
            return static::event($id);
        }, $ids);

        $data = [
            '_embedded' => [
                'events' => $events,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/chat/v1/$chatId/events",
                ],
                'next' => [
                    'href' => "https://api.helpscout.net/chat/v1/$chatId/events?page=2",
                ],
                'first' => [
                    'href' => "https://api.helpscout.net/chat/v1/$chatId/events?page=1",
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/chat/v1/$chatId/events?page=$totalPages",
                ],
                'page' => [
                    'href' => "https://api.helpscout.net/chat/v1/$chatId/events{?page}",
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

    private static function chat(string $id): array
    {
        return [
            'id' => $id,
            'beaconId' => (string) Uuid::uuid4(),
            'mailboxId' => 12,
            'createdAt' => '2021-04-12T08:44:34.736330Z',
            'assignee' => [
                'id' => 1,
                'type' => 'user',
                'first' => 'Tom',
                'last' => 'Graham',
                'email' => 'tom@helpscout.com',
            ],
            'customer' => [
                'id' => 2,
                'type' => 'customer',
                'first' => 'Denny',
                'last' => 'Swindle',
                'email' => 'denny@helpscout.com',
            ],
            'preview' => 'Preview text',
            'tags' => [
                [
                    'id' => null,
                    'slug' => 'test',
                    'color' => 'none',
                ],
            ],
            'timeline' => [
                [
                    'type' => 'chat-started',
                    'timestamp' => '2021-04-12T08:44:34.542000Z',
                    'url' => 'https://fiddle.jshell.net',
                    'title' => 'Untitled Page',
                ],
            ],
            '_embedded' => [
                'events' => [
                    self::event((string) Uuid::uuid4()),
                    self::event((string) Uuid::uuid4()),
                    self::event((string) Uuid::uuid4()),
                    self::event((string) Uuid::uuid4()),
                    self::event((string) Uuid::uuid4()),
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/chat/v1/$id",
                ],
            ],
        ];
    }

    private static function event(string $id): array
    {
        return [
            'id' => $id,
            'type' => 'message',
            'action' => 'message-added',
            'author' => [
                'id' => 1,
                'type' => 'user',
                'first' => 'Tom',
                'last' => 'Graham',
                'email' => 'tom@helpscout.com',
            ],
            'createdAt' => '2021-04-12T08:44:48.718742Z',
            'params' => [
                'test' => 'value',
            ],
            '_links' => [
                'author' => [
                    'href' => 'https://api.helpscout.net/v2/users/1',
                ],
            ],
        ];
    }
}
