<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Customers\Entry;

use HelpScout\Api\Customers\Entry\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testHydrate()
    {
        $email = new Email();
        $email->hydrate([
            'id' => 12,
            'value' => 'tom@helpscout.com',
            'type' => 'work',
        ]);

        $this->assertSame(12, $email->getId());
        $this->assertSame('tom@helpscout.com', $email->getValue());
        $this->assertSame('work', $email->getType());
    }

    public function testExtract()
    {
        $email = new Email();
        $email->setId(12);
        $email->setValue('tom@helpscout.com');
        $email->setType('work');

        $this->assertSame([
            'value' => 'tom@helpscout.com',
            'type' => 'work',
        ], $email->extract());
    }

    public function testExtractNewEntity()
    {
        $email = new Email();

        $this->assertSame([
            'value' => null,
            'type' => null,
        ], $email->extract());
    }
}
