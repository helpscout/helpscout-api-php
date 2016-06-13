<?php
namespace HelpScout;

use HelpScout\model\ref\customfields\DateFieldRef;
use HelpScout\model\ref\customfields\DropdownFieldRef;
use HelpScout\model\ref\customfields\MultiLineFieldRef;
use HelpScout\model\ref\customfields\NumberFieldRef;
use HelpScout\model\ref\customfields\SingleLineFieldRef;

class CustomFieldFactory
{
    public static function fromMailbox(array $attributes)
    {
        $map = array(
            'fieldName' => 'name',
            'fieldType' => 'type'
        );

        return static::createField(
            $attributes['fieldType'],
            static::mapAttributes($attributes, $map)
        );
    }

    public static function fromConversation(array $attributes)
    {
        $map = array(
            'fieldId' => 'id'
        );

        return static::createField(
            $attributes['type'],
            static::mapAttributes($attributes, $map)
        );
    }

    public static function createField($type, array $attributes)
    {
        $attributes = (object) $attributes;
        switch ($type) {
            case 'SINGLE_LINE' :
                return new SingleLineFieldRef($attributes);
                break;
            case 'MULTI_LINE' :
                return new MultiLineFieldRef($attributes);
                break;
            case 'DROPDOWN' :
                return new DropdownFieldRef($attributes);
                break;
            case 'DATE' :
                return new DateFieldRef($attributes);
                break;
            case 'NUMBER' :
                return new NumberFieldRef($attributes);
                break;
        }

        throw new \InvalidArgumentException($type . ' is not a supported Custom Field type.');
    }

    protected static function mapAttributes(array $attributes, array $map)
    {
        $temp = array();

        foreach ($attributes as $key => $value) {
            if (array_key_exists($key, $map)) {
                $temp[$map[$key]] = $value;
            } else {
                $temp[$key] = $value;
            }
        }

        return $temp;
    }
}
