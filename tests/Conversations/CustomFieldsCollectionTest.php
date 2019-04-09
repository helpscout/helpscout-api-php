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
        $customField->setId(936);
        $customField->setName('Account Type');
        $customField->setValue('Administrator');

        $customFieldsCollection->setCustomFields([
            $customField,
        ]);

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
