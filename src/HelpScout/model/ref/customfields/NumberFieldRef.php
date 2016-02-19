<?php
namespace HelpScout\model\ref\customfields;

use HelpScout\ValidationException;

class NumberFieldRef extends AbstractCustomFieldRef
{

    public function validate($value)
    {
        if (!is_numeric($value)) {
            throw new ValidationException('The value must be numeric');
        }
    }
}
