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
        for ($i = 0; $i < $pageElements; $i++) {
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
            '_links' => [
                'author' => [
                    'href' => '',
                ],
            ],
        ];
    }
}
