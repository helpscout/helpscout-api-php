<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Mailboxes\Entry;

use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Mailboxes\Entry\Field;
use HelpScout\Api\Mailboxes\Entry\FieldOption;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testHydrateDropdown()
    {
        $field = new Field();
        $field->hydrate([
            'id' => 12,
            'name' => 'Beers',
            'type' => Field::TYPE_DROPDOWN,
            'order' => 1,
            'required' => false,
            'options' => [
                [
                    'id' => 168,
                    'order' => 1,
                    'label' => 'IPA',
                ],
                [
                    'id' => 170,
                    'order' => 2,
                    'label' => 'Stout',
                ],
            ],
        ]);

        $this->assertSame(12, $field->getId());
        $this->assertSame('Beers', $field->getName());
        $this->assertSame(Field::TYPE_DROPDOWN, $field->getType());
        $this->assertSame(1, $field->getOrder());
        $this->assertFalse($field->isRequired());

        $options = $field->getOptions();
        $this->assertCount(2, $options);

        $this->assertSame(168, $options[0]->getId());
        $this->assertSame(1, $options[0]->getOrder());
        $this->assertSame('IPA', $options[0]->getLabel());

        $this->assertSame(170, $options[1]->getId());
        $this->assertSame(2, $options[1]->getOrder());
        $this->assertSame('Stout', $options[1]->getLabel());
    }

    public function testMailboxFieldOptions()
    {
        $field = new Field();
        $this->assertEmpty($field->getOptions());

        $options = new Collection([new FieldOption()]);
        $field->setOptions($options);
        $this->assertCount(1, $field->getOptions());

        $option = new FieldOption();
        $field->addOption($option);
        $this->assertSame($option, $field->getOptions()->toArray()[1]);
    }
}
