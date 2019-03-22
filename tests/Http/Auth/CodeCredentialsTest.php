<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Auth;

use HelpScout\Api\Http\Auth\CodeCredentials;
use PHPUnit\Framework\TestCase;

class CodeCredentialsTest extends TestCase
{
    public function testBuildsPayload()
    {
        $appId = '123512362';
        $appSecret = 's5df5634s';
        $code = '58785656';

        $credentials = new CodeCredentials($appId, $appSecret, $code);

        $this->assertSame([
            'grant_type' => CodeCredentials::TYPE,
            'code' => $code,
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ], $credentials->getPayload());
    }
}
