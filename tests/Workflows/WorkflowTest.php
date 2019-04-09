<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Workflows;

use HelpScout\Api\Workflows\Workflow;
use PHPUnit\Framework\TestCase;

class WorkflowTest extends TestCase
{
    public function testHydrateAndExtract()
    {
        $data = [
            'id' => 123,
            'mailboxId' => 321,
            'status' => 'active',
            'type' => 'manual',
            'order' => 1,
            'name' => 'Automagic',
            'createdAt' => '2010-02-10T09:00:00Z',
            'modifiedAt' => '2010-02-10T10:37:00Z',
        ];

        $workflow = new Workflow();
        $workflow->hydrate($data);
        $this->assertTrue($workflow->isManual());

        $this->assertSame($data, $workflow->extract());

        $workflow->setAutomatic();
        $this->assertTrue($workflow->isAutomatic());

        $workflow->setManual();
        $this->assertTrue($workflow->isManual());
    }
}
