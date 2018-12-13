<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class ConversationPayloads
{
    public static function getConversation(int $id): string
    {
        return json_encode(static::conversation($id));
    }

    public static function getConversations(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $conversations = array_map(function ($id) {
            return static::conversation($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'conversations' => $conversations,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/conversations',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/conversations?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/conversations?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/conversations?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/conversations{?page}',
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

    private static function conversation(int $id): array
    {
        return [
            'id' => $id,
            'number' => 15473,
            'threads' => 2,
            'type' => 'email',
            'folderId' => 493,
            'status' => 'closed',
            'state' => 'published',
            'subject' => 'Need Help',
            'preview' => "I'm having a hard time resolving this",
            'mailboxId' => 85,
            'assignee' => [
                'id' => 256,
                'first' => 'Mr',
                'last' => 'Robot',
                'email' => 'none@nowhere.com',
            ],
            'createdBy' => [
                'id' => 12,
                'type' => 'customer',
                'email' => 'bear@acme.com',
            ],
            'createdAt' => '2017-04-21T14:39:56Z',
            'closedBy' => 17,
            'closedAt' => '2017-04-21T14:43:24Z',
            'userUpdatedAt' => '2017-04-21T14:43:24Z',
            'customerWaitingSince' => [
                'time' => '2012-07-24T20:18:33Z',
                'friendly' => '20 hours ago',
                'latestReplyFrom' => 'customer',
            ],
            'source' => [
                'type' => 'email',
                'via' => 'customer',
            ],
            'tags' => [
                [
                    'id' => 9150,
                    'color' => '#929499',
                    'tag' => 'vip',
                ],
            ],
            'cc' => [
                'bear@normal.com',
            ],
            'bcc' => [
                'bear@secret.com',
            ],
            'primaryCustomer' => [
                'id' => 238604,
            ],
            'customFields' => [
                [
                    'id' => 6688,
                    'name' => 'Account Type',
                    'value' => '33077',
                ],
            ],
            '_links' => [
                'assignee' => [
                    'href' => 'https://api.helpscout.net/v2/users/256',
                ],
                'closedBy' => [
                    'href' => 'https://api.helpscout.net/v2/users/17',
                ],
                'createdByCustomer' => [
                    'href' => 'https://api.helpscout.net/v2/customers/12',
                ],
                'mailbox' => [
                    'href' => 'https://api.helpscout.net/v2/mailboxes/85',
                ],
                'primaryCustomer' => [
                    'href' => 'https://api.helpscout.net/v2/customers/238604',
                ],
                'threads' => [
                    'href' => "https://api.helpscout.net/v2/conversations/$id/threads",
                ],
                'self' => [
                    'href' => "https://api.helpscout.net/v2/conversations/$id",
                ],
            ],
        ];
    }

    public static function getThreads(int $conversationId): string
    {
        return json_encode([
            '_embedded' => [
                'threads' => [
                    [
                        'id' => 1,
                        'value' => 'jsprout',
                        'type' => 'aim',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/conversations/$conversationId/chats/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$conversationId/chats",
                ],
            ],
        ]);
    }
}
