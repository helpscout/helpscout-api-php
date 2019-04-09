<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class WorkflowPayloads
{
    /**
     * @param int $id
     *
     * @return string
     */
    public static function getWebhook(int $id): string
    {
        return json_encode(static::workflow($id));
    }

    /**
     * @param int $pageNumber
     * @param int $totalElements
     *
     * @return string
     */
    public static function getWorkflows(int $pageNumber, int $totalElements): string
    {
        $pageSize = 10;
        $pageElements = min($totalElements, $pageSize);
        $totalPages = ceil($totalElements / $pageSize);

        // Create embedded resources
        $workflows = array_map(function ($id) {
            return static::workflow($id);
        }, range(1, $pageElements));

        $data = [
            '_embedded' => [
                'workflows' => $workflows,
            ],
            'page' => [
                'size' => $pageSize,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
                'number' => $pageNumber,
            ],
            '_links' => [
                'self' => [
                    'href' => 'https://api.helpscout.net/v2/workflows',
                ],
                'next' => [
                    'href' => 'https://api.helpscout.net/v2/workflows?page=2',
                ],
                'first' => [
                    'href' => 'https://api.helpscout.net/v2/workflows?page=1',
                ],
                'last' => [
                    'href' => "https://api.helpscout.net/v2/workflows?page=$totalPages",
                ],
                'page' => [
                    'href' => 'https://api.helpscout.net/v2/workflows{?page}',
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
    private static function workflow(int $id): array
    {
        return [
            'id' => $id,
            'mailboxId' => 321,
            'status' => 'active',
            'type' => 'manual',
            'order' => 1,
            'name' => 'Automagic',
            'createdAt' => '2010-02-10T09:00:00Z',
            'modifiedAt' => '2010-02-10T10:37:00Z',
        ];
    }
}
