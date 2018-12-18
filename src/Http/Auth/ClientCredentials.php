<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

class ClientCredentials implements Auth
{
    public const TYPE = 'client_credentials';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appSecret;

    /**
     * ClientCredentials constructor.
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct(string $appId, string $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return [
            'grant_type' => $this->getType(),
            'client_id' => $this->getAppId(),
            'client_secret' => $this->getAppSecret(),
        ];
    }
}
