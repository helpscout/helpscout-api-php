<?php

declare(strict_types=1);

namespace HelpScout\Api\Reports;

class ParameterBagFactory
{
    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $originalParams;

    /**
     * @var array
     */
    private $params = [];

    /**
     * ParameterBagFactory constructor.
     *
     * @param array $fields
     * @param array $params
     */
    public function __construct(array $fields, array $params)
    {
        $this->fields = $fields;
        $this->originalParams = $params;
        $this->prepareFields();
    }

    /**
     * @return ParameterBag
     */
    public function build(): ParameterBag
    {
        return new ParameterBag($this->params);
    }

    private function prepareFields(): void
    {
        foreach ($this->fields as $field) {
            $value = $this->originalParams[$field] ?? false;
            if ($value) {
                $this->prepareField($field, $value);
            }
        }
    }

    /**
     * @param string $field
     * @param mixed  $value
     */
    private function prepareField(string $field, $value): void
    {
        switch ($field) {
            case 'start':
            case 'end':
            case 'previousStart':
            case 'previousEnd':
                $this->formatDateInterval($field, $value);
                break;
            case 'mailboxes':
            case 'tags':
            case 'types':
            case 'folders':
            case 'sites':
                $this->formatArray($field, $value);
                break;
            case 'officeHours':
                $this->params[$field] = (bool) $value;
                break;
            default:
                $this->params[$field] = $value;
                break;
        }
    }

    /**
     * @param string $field
     * @param mixed  $date
     */
    private function formatDateInterval(string $field, $date): void
    {
        if ($date instanceof \DateTimeInterface) {
            $date = $date->format(Report::DATE_FORMAT);
        }

        $this->params[$field] = $date;
    }

    /**
     * @param string $field
     * @param mixed  $values
     */
    private function formatArray(string $field, $values): void
    {
        if (\is_array($values)) {
            $values = \implode(',', $values);
        }
        $this->params[$field] = $values;
    }
}
