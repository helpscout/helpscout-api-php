<?php

declare(strict_types=1);

namespace HelpScout\Api\Workflows;

use HelpScout\Api\Entity\Extractable;

class WorkflowBatch implements Extractable
{
    /**
     * @var array
     */
    private $conversations;

    /**
     * WorkflowBatch constructor.
     *
     * @param array $conversations
     */
    public function __construct(array $conversations = [])
    {
        $this->conversations = $conversations;
    }

    /**
     * @return array
     */
    public function extract(): array
    {
        return $this->conversations;
    }
}
