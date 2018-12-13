<?php

declare(strict_types=1);

namespace HelpScout\Api\Workflows;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Entity\Patch;

class WorkflowsEndpoint extends Endpoint
{
    public const RUN_WORKFLOW_URL = '/v2/workflows/%d/run';
    public const LIST_WORKFLOWS_URI = '/v2/workflows';
    public const PATCH_STATUS_URI = '/v2/workflows/%d';
    public const RESOURCE_KEY = 'workflows';
    public const VALID_STATUS = [
        'active',
        'inactive',
    ];

    /**
     * Run a manual workflow on a list of conversations.
     *
     * @param int   $workflowId
     * @param array $convos
     */
    public function runWorkflow(int $workflowId, array $convos): void
    {
        Assert::notEmpty(
            $convos,
            'Conversations cannot be empty'
        );

        $conversations = new RunWorkflowRequest($convos);

        $uri = sprintf(self::RUN_WORKFLOW_URL, $workflowId);

        foreach ($conversations->getBatch() as $batch) {
            $this->restClient->createResource($batch, $uri);
        }
    }

    public function updateStatus(int $id, string $status): void
    {
        Assert::oneOf(
            $status,
            self::VALID_STATUS,
            'Status must be one of "inactive" or "active"'
        );

        $this->restClient->patchResource(
            new Patch($status, 'replace', '/status'),
            sprintf(self::PATCH_STATUS_URI, $id)
        );
    }

    /**
     * @return PagedCollection
     */
    public function list(): PagedCollection
    {
        return $this->loadPage(
            Workflow::class,
            self::RESOURCE_KEY,
            self::LIST_WORKFLOWS_URI
        );
    }
}
