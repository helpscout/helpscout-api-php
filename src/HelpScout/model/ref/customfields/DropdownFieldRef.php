<?php
namespace HelpScout\model\ref\customfields;

use HelpScout\ValidationException;

class DropdownFieldRef extends AbstractCustomFieldRef
{

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function validate($value)
    {
        $optionIds = array();

        foreach ($this->getOptions() as $option) {
            $option = (array) $option;
            $optionIds[] = $option['id'];
        }

        if (!in_array($value, $optionIds)) {
            throw new ValidationException('The dropdown value must be the ID of one of the options');
        }
    }
}
