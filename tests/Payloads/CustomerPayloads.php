<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class CustomerPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getCustomer(int $id): string
    {
        return json_encode(static::customer($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getCustomers(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $customers = array_map(function ($id) {
            return static::customer($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'customers' => $customers,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/customers',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/customers?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/customers?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/customers?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/customers{?page}',
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
    private static function customer(int $id): array
    {
        return [
            'id' => $id,
            'firstName' => 'Big',
            'lastName' => 'Bird',
            'gender' => 'Unknown',
            'jobTitle' => 'Entertainer',
            'location' => 'US',
            'organization' => 'Sesame Street',
            'photoType' => 'unknown',
            'photoUrl' => '',
            'createdAt' => '2017-04-21T14:39:56Z',
            'updatedAt' => '2017-04-21T14:43:24Z',
            'background' => 'Big yellow bird',
            'age' => '52',
            '_links' => [
                'address' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id/address",
                ],
                'chats' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id/chats",
                ],
                'emails' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id/emails",
                ],
                'phones' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id/phones",
                ],
                'social-profiles' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id/social-profiles",
                ],
                'websites' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id/websites",
                ],
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$id",
                ],
            ],
        ];
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    public static function getAddress(int $customerId): string
    {
        return json_encode([
            'city' => 'Dallas',
            'lines' => ['123 West Main St', 'Suite 123'],
            'state' => 'TX',
            'postalCode' => '74206',
            'country' => 'US',
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$customerId/address",
                ],
            ],
        ]);
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    public static function getChats(int $customerId): string
    {
        return json_encode([
            '_embedded' => [
                'chats' => [
                    [
                        'id' => 1,
                        'value' => 'jsprout',
                        'type' => 'aim',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/customers/$customerId/chats/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$customerId/chats",
                ],
            ],
        ]);
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    public static function getEmails(int $customerId): string
    {
        return json_encode([
            '_embedded' => [
                'emails' => [
                    [
                        'id' => 1,
                        'value' => 'bigbird@sesamestreet.com',
                        'type' => 'work',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/customers/$customerId/emails/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$customerId/emails",
                ],
            ],
        ]);
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    public static function getPhones(int $customerId): string
    {
        return json_encode([
            '_embedded' => [
                'phones' => [
                    [
                        'id' => 1,
                        'value' => '222-333-4444',
                        'type' => 'work',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/customers/$customerId/phones/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$customerId/phones",
                ],
            ],
        ]);
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    public static function getSocialProfiles(int $customerId): string
    {
        return json_encode([
            '_embedded' => [
                'social-profiles' => [
                    [
                        'id' => 1,
                        'value' => 'bigbird22',
                        'type' => 'twitter',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/customers/$customerId/social-profiles/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$customerId/social-profiles",
                ],
            ],
        ]);
    }

    /**
     * @param int $customerId
     *
     * @return string
     */
    public static function getWebsites(int $customerId): string
    {
        return json_encode([
            '_embedded' => [
                'websites' => [
                    [
                        'id' => 1,
                        'value' => 'https://www.sesamestreet.com',
                        '_links' => [
                            'self' => [
                                'href' => "https://api.helpscout.net/v2/customers/$customerId/websites/1",
                            ],
                        ],
                    ],
                ],
            ],
            '_links' => [
                'self' => [
                    'href' => "https://api.helpscout.net/v2/customers/$customerId/websites",
                ],
            ],
        ]);
    }
}
