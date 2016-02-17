<?php

use HelpScout\model\ref\customfields\NumberFieldRef;

class NumberFieldRefTest extends TestCase
{
    /**
     * @group customfields
     * @expectedException \HelpScout\ValidationException
     */
    public function testValidationFailsWhenValueIsNotNumeric()
    {
        $field = new NumberFieldRef;
        $field->setValue('foo');
    }
}
