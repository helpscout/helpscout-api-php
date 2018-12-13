<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Payloads;

class ErrorPayloads
{
    /**
     * @return string
     */
    public static function validationErrors(): string
    {
        return json_encode([
            'logRef' => 'cb73aad3-9a81-44fd-bf70-48250ea256ff#12',
            'message' => 'Validation error',
            '_embedded' => [
                'errors' => [
                    [
                        'path' => 'country',
                        'message' => 'may not be empty',
                        'links' => [
                            [
                                'rel' => 'about',
                                'href' => 'http://developer.helpscout.net/help-desk-api-v2/errors#1',
                            ],
                        ],
                    ],
                ],
            ],
            'links' => [
                [
                    'rel' => 'about',
                    'href' => 'http://developer.helpscout.net/help-desk-api-v2/errors',
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public static function internalServerError(): string
    {
        return json_encode([
            'logRef' => 'cb73aad3-9a81-44fd-bf70-48250ea256ff#12',
            'message' => 'Internal error',
            'links' => [
                [
                    'rel' => 'about',
                    'href' => 'http://developer.helpscout.net/help-desk-api-v2/errors',
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public static function rateLimitExceededError(): string
    {
        return json_encode([
            'message' => 'API rate limit exceeded',
        ]);
    }
}
