<?php
namespace HelpScout\model\ref\customfields;

use HelpScout\ValidationException;

class DateFieldRef extends AbstractCustomFieldRef
{

    public function validate($value)
    {
        $didError = false;
        $matches = array();
        if ($value !== '') {
            if (! (bool) preg_match('/\d{4}-\d{2}-\d{2}/', $value, $matches)) {
                $didError = true;
            } else {
                // Grab the date string from the date value, if anything is left (timestamp or time zone)
                // count it as an error.
                if (isset($matches[0])) {
                    $trimmed = trim(str_replace($matches[0], '', $value));
                    if (! empty($trimmed)) {
                        $didError = true;
                    }
                }
                if (!$didError) {
                    $millis = strtotime($value);
                    if ($millis === false) {
                        $didError = true;
                    }
                    $dateInfo = getdate($millis);
                    if ($dateInfo) {
                        $didError = !checkdate($dateInfo['mon'], $dateInfo['mday'], $dateInfo['year']);
                    }
                }
            }
            if ($didError) {
                throw new ValidationException(sprintf(
                    'Date fields must be in the format YYYY-MM-DD (ex: %s).',
                    date('Y-m-d')
                ));
            }
        }
    }
}
