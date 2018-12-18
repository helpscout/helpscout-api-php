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
     *
     * @param string $appId
     * @param string $appSecret
     * @param string $refreshToken
     */
    public function __construct(string $appId, string $appSecret, string $refreshToken)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->refreshToken = $refreshToken;
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

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
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
            'refresh_token' => $this->getRefreshToken(),
            'client_id' => $this->getAppId(),
            'client_secret' => $this->getAppSecret(),
        ];
    }
}
