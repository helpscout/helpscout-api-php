<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use HelpScout\Api\Conversations\CustomField;
use PHPUnit\Framework\TestCase;

class CustomFieldTest extends TestCase
{
    public function testHydrate()
    {
        $customField = new CustomField();
        $customField->hydrate([
            'id' => 6688,
            'name' => 'Account Type',
            'value' => 'Premium',
        ]);

        $this->assertSame(6688, $customField->getId());
        $this->assertSame('Account Type', $customField->getName());
        $this->assertSame('Premium', $customField->getValue());
    }

    public function testExtract()
    {
        $customField = new CustomField();
        $customField->setId(6688);
        $customField->setName('Account Type');
        $customField->setValue('Premium');

        $this->assertSame([
            'id' => 6688,
            'name' => 'Account Type',
            'value' => 'Premium',
        ], $customField->extract());
    }

    public function testExtractFormatsDateTime()
    {
        $customField = new CustomField();
        $customField->setId(6688);
        $customField->setName('Account Type');
        $customField->setValue(new \DateTime('Jan 2 2017'));

        $this->assertSame([
            'id' => 6688,
            'name' => 'Account Type',
            'value' => '2017-01-02',
        ], $customField->extract());
    }

    public function testExtractNewEntity()
    {
        $customField = new CustomField();

        $this->assertSame([
            'id' => null,
            'name' => null,
            'value' => null,
        ], $customField->extract());
    }
}
