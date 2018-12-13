<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class WebhookPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getWebhook(int $id): string
    {
        return json_encode(static::webhook($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getWebhooks(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $webhooks = array_map(function ($id) {
            return static::webhook($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'webhooks' => $webhooks,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/webhooks',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/webhooks?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/webhooks?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/webhooks?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/webhooks{?page}',
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
    private static function webhook(int $id): array
    {
        return [
            'id' => $id,
            'url' => 'http://bad-url.com',
            'state' => 'disabled',
            'events' => ['convo.assigned', 'convo.moved'],
            'secret' => 'mZ9XbGHodX',
        ];
    }
}
