<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class ThreadPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getThread(int $id): string
    {
        return json_encode(static::thread($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getThreads(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $threads = array_map(function ($id) {
            return static::thread($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'threads' => $threads,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/conversations/1/threads?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/conversations/1/threads{?page}',
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
    private static function thread(int $id): array
    {
        return [
            'id' => $id,
            'type' => 'customer',
            'status' => 'active',
            'state' => 'published',
            'action' => [
                'type' => 'manual-workflow',
                'text' => 'You ran the Assign to Spam workflow',
            ],
            'body' => 'Need Help',
            'source' => [
                'type' => 'email',
                'via' => 'user',
            ],
            'createdBy' => [
                'id' => 6857,
                'type' => 'customer',
            ],
            'assignedTo' => [
                'id' => 1234,
                'type' => 'team',
                'first' => 'Jack',
                'last' => 'Sprout',
                'email' => 'bear@acme.com',
            ],
            'savedReplyId' => 17142,
            'customer' => [
                'id' => 256,
                'email' => 'vbird@mywork.com',
            ],
            'to' => [
                'bird@normal.com',
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'createdAt' => '2017-04-21T14:39:56Z',
        ];
    }
}
