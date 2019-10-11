<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Conversations;

use HelpScout\Api\Conversations\CustomField;
use HelpScout\Api\Conversations\CustomFieldsCollection;
use PHPUnit\Framework\TestCase;

class CustomFieldsCollectionTest extends TestCase
{
    public function testExtract()
    {
        $customFieldsCollection = new CustomFieldsCollection();

        $customField = new CustomField();
        $this->assertInstanceOf(CustomField::class, $customField->setId(936));
        $this->assertInstanceOf(CustomField::class, $customField->setName('Account Type'));
        $this->assertInstanceOf(CustomField::class, $customField->setValue('Administrator'));

        $this->assertInstanceOf(CustomFieldsCollection::class, $customFieldsCollection->setCustomFields([
            $customField,
        ]));

        $this->assertEquals([
            'fields' => [
                [
                    'id' => 936,
                    'name' => 'Account Type',
                    'value' => 'Administrator',
                ],
            ],
        ], $customFieldsCollection->extract());
    }
}
