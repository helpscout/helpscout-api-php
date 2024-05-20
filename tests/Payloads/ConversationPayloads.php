<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class ConversationPayloads
{
    public static function getConversation(int $id): string
    {
        return json_encode(static::conversation($id));
    }

    public static function getConversations(int $pageNumber, int $totalElements, bool $embedThread = false): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $conversations = array_map(function ($id) use ($embedThread) {
            return static::conversation($id, $embedThread);
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

    private static function conversation(int $id, bool $embedThread = false): array
    {
        $conversation = [
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

        if ($embedThread) {
            $conversation['_embedded']['threads'] = [
                [
                    'id' => 1,
                    'type' => 'customer',
                    'status' => 'active',
                    'state' => 'published',
                    'action' => [
                        'type' => 'default',
                        'associatedEntities' => [],
                    ],
                    'body' => 'This is a test',
                    'source' => [
                        'type' => 'email',
                        'via' => 'user',
                    ],
                    'customer' => [
                        'id' => 472611182,
                        'first' => 'John',
                        'last' => 'Doe',
                        'photoUrl' => 'https://d33v4339jhl8k0.cloudfront.net/customer-avatar/05.png',
                        'email' => 'john.doe@example.com',
                    ],
                    'createdBy' => [
                        'id' => 1,
                        'type' => 'user',
                        'first' => 'John',
                        'last' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'to' => [],
                        'cc' => [],
                        'bcc' => [],
                        'createdAt' => '2017-04-21T14:39:56Z',
                    ],
                    'assignedTo' => [
                        'id' => 12,
                        'first' => 'Help',
                        'last' => 'Scout',
                        'email' => 'none@nowhere.com',
                    ],
                    'savedReplyId' => 0,
                    '_embedded' => [
                        'attachments' => [],
                    ],
                    '_links' => [
                        'createdByUser' => [
                            'href' => 'https://api.helpscout.net/v2/users/1',
                        ]
                    ]
                ],
            ];
        }

//        var_dump($conversation);

        return $conversation;
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
