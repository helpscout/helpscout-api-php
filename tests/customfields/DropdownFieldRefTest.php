<?php

use HelpScout\model\ref\customfields\DropdownFieldRef;

class DropdownFieldRefTest extends TestCase
{
    /**
     * @group customfields
     * @expectedException \HelpScout\ValidationException
     */
    public function testValidationFailsWhenValueIsNotAnOptionId()
    {
        $field = $this->getField();
        $field->setValue(18);
    }

    /**
     * @group customfields
     */
    public function testValidationPassesWhenValueIsAnOptionId()
    {
        $field = $this->getField();
        $field->setValue(12);

        $this->assertTrue(true);
    }

    private function getField()
    {
        $field = new DropdownFieldRef;
        $field->setOptions(array(
            array('id' => 12, 'label' => 'Blue', 'order' => 1),
            array('id' => 3, 'label' => 'Red', 'order' => 2),
            array('id' => 6, 'label' => 'Green', 'order' => 3)
        ));

        return $field;
    }
}
