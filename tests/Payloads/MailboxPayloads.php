<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

use HelpScout\Api\Mailboxes\Entry\Field;

class MailboxPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getMailbox(int $id): string
    {
        return json_encode(static::mailbox($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getMailboxes(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $mailboxes = array_map(function ($id) {
            return static::mailbox($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'mailboxes' => $mailboxes,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/mailboxes',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/mailboxes?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/mailboxes?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/mailboxes?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/mailboxes{?page}',
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
    private static function mailbox(int $id): array
    {
        return [
            'id' => $id,
            'name' => 'ACME Support',
            'slug' => '83ebc5d87292a795',
            'email' => 'support@acme.com',
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            '_links' => [
                'fields' => [
                    'href' => "https://api.helpscout.net/v2/mailboxes/$id/fields",
                ],
                'folders' => [
                    'href' => "https://api.helpscout.net/v2/mailboxes/$id/folders",
                ],
                'self' => [
                    'href' => "https://api.helpscout.net/v2/mailboxes/$id",
                ],
            ],
        ];
    }

    /**
     * @param int $mailboxId
     *
     * @return string
     */
    public static function getFields(int $mailboxId): string
    {
        return json_encode([
            '_embedded' => [
                'fields' => [
                    [
                        'id' => 1,
                        'name' => 'Beers',
                        'type' => Field::TYPE_DROPDOWN,
                        'order' => 1,
                        'required' => false,
                        'options' => [
                            [
                                'id' => 1,
                                'order' => 1,
                                'label' => 'IPA',
                            ],
                            [
                                'id' => 2,
                                'order' => 2,
                                'label' => 'Stout',
                            ],
                        ],
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/mailboxes/$mailboxId/field/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/mailboxes/$mailboxId/fields",
                ],
            ],
        ]);
    }

    /**
     * @param int $mailboxId
     *
     * @return string
     */
    public static function getFolders(int $mailboxId): string
    {
        return json_encode([
            '_embedded' => [
                'folders' => [
                    [
                        'id' => 1,
                        'name' => 'My Tickets',
                        'type' => 'mytickets',
                        'userId' => 1,
                        'totalCount' => 200,
                        'activeCount' => 100,
                        'updatedAt' => '2017-04-21T14:43:24Z',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/mailboxes/$mailboxId/folders/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/mailboxes/$mailboxId/folders",
                ],
            ],
        ]);
    }
}
