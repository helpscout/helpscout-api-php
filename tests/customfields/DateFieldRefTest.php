<?php

use HelpScout\model\ref\customfields\DateFieldRef;

class DateFieldRefTest extends TestCase
{
    /**
     * @group customfields
     * @expectedException \HelpScout\ValidationException
     */
    public function testValidationFailsWhenValueIsProperlyFormatted()
    {
        $field = new DateFieldRef;
        $field->setValue('12-25-2015');
    }

    /**
     * @group customfields
     */
    public function testValidationPassesWhenValueIsAnOptionId()
    {
        $field = new DateFieldRef;
        $field->setValue('2015-12-25');

        $this->assertTrue(true);
    }
}
