<?php

use HelpScout\model\ref\customfields\SingleLineFieldRef;

class SingleLineFieldRefTest extends TestCase
{
    /**
     * @group customfields
     * @expectedException \HelpScout\ValidationException
     */
    public function testValidationFailsWhenLengthExceedsLimit()
    {
        $field = new SingleLineFieldRef;
        $field->setValue(str_repeat('s', SingleLineFieldRef::LIMIT + 1));
    }
}
