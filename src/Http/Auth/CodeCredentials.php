<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Auth;

class CodeCredentials implements Auth
{
    public const TYPE = 'authorization_code';

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
    private $code;

    /**
     * CodeCredentials constructor.
     */
    public function __construct(string $appId, string $appSecret, string $code)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->code = $code;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getPayload(): array
    {
        return [
            'grant_type' => $this->getType(),
            'code' => $this->getCode(),
            'client_id' => $this->getAppId(),
            'client_secret' => $this->getAppSecret(),
        ];
    }
}
