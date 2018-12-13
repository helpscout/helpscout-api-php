<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\SocialProfile;
use PHPUnit\Framework\TestCase;

class SocialProfileTest extends TestCase
{
    public function testHydrate()
    {
        $socialProfile = new SocialProfile();
        $socialProfile->hydrate([
            'id' => 12,
            'value' => 'tompedals',
            'type' => 'twitter',
        ]);

        $this->assertSame(12, $socialProfile->getId());
        $this->assertSame('tompedals', $socialProfile->getValue());
        $this->assertSame('twitter', $socialProfile->getType());
    }

    public function testExtract()
    {
        $socialProfile = new SocialProfile();
        $socialProfile->setId(12);
        $socialProfile->setValue('tompedals');
        $socialProfile->setType('twitter');

        $this->assertSame([
            'value' => 'tompedals',
            'type' => 'twitter',
        ], $socialProfile->extract());
    }

    public function testExtractNewEntity()
    {
        $socialProfile = new SocialProfile();

        $this->assertSame([
            'value' => null,
            'type' => null,
        ], $socialProfile->extract());
    }
}
