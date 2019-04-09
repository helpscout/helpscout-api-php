<?php

declare(strict_types=1);

namespace HelpScout\Api\Workflows;

class RunWorkflowRequest
{
    public const MAX_BATCH_SIZE = 50;

    /**
     * @var array
     */
    private $batches;

    /**
     * RunWorkflowRequest constructor.
     *
     * @param array $conversations
     */
    public function __construct(array $conversations = [])
    {
        $this->batches = array_chunk($conversations, self::MAX_BATCH_SIZE);
    }

    public function getBatch(): ?\Generator
    {
        foreach ($this->batches as $batch) {
            yield new WorkflowBatch($batch);
        }
    }
}
