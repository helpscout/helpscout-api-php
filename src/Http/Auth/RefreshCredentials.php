<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

class RefreshCredentials implements Auth
{
    public const TYPE = 'refresh_token';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appSecret;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * ClientCredentials constructor.
     */
    public function __construct(string $appId, string $appSecret, string $refreshToken)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->refreshToken = $refreshToken;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getPayload(): array
    {
        return [
            'grant_type' => $this->getType(),
            'refresh_token' => $this->getRefreshToken(),
            'client_id' => $this->getAppId(),
            'client_secret' => $this->getAppSecret(),
        ];
    }
}
