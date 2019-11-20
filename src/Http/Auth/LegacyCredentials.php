<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

/**
 * Class LegacyCredentials.
 *
 * @deprecated
 */
class LegacyCredentials implements Auth
{
    public const TYPE = 'legacy_credentials';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * ClientCredentials constructor.
     */
    public function __construct(string $clientId, string $apiKey)
    {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getPayload(): array
    {
        return [
            'clientId' => $this->getClientId(),
            'apiKey' => $this->getApiKey(),
        ];
    }
}
