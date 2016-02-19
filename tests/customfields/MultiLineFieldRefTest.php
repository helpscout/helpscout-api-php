<?php

use HelpScout\model\ref\customfields\MultiLineFieldRef;

class MultiLineFieldRefTest extends TestCase
{
    /**
     * @group customfields
     * @expectedException \HelpScout\ValidationException
     */
    public function testValidationFailsWhenLengthExceedsLimit()
    {
        $field = new MultiLineFieldRef;
        $field->setValue(str_repeat('s', MultiLineFieldRef::LIMIT + 1));
    }
}
