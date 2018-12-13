<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use HelpScout\Api\Entity\Extractable;

/**
 * This collection is only used when updating the CustomFields on a conversation.  It's only required because
 * we need a "fields" key populated with the collection of fields.
 */
class CustomFieldsCollection implements Extractable
{
    /** @var array */
    private $customFields;

    public function extract(): array
    {
        $fields = [];
        foreach ($this->getCustomFields() as $field) {
            $fields[] = $field->extract();
        }

        return [
            'fields' => $fields,
        ];
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields): void
    {
        $this->customFields = $customFields;
    }
}
