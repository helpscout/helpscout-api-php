<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Mailboxes;

use HelpScout\Api\Mailboxes\Entry\Field;
use HelpScout\Api\Mailboxes\Entry\Folder;
use HelpScout\Api\Mailboxes\Mailbox;
use HelpScout\Api\Mailboxes\MailboxRequest;
use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\MailboxPayloads;

/**
 * @group integration
 */
class MailboxClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testGetMailbox()
    {
        $this->stubResponse(
            $this->getResponse(200, MailboxPayloads::getMailbox(1))
        );

        $mailbox = $this->client->mailboxes()->get(1);

        $this->assertSame(1, $mailbox->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/mailboxes/1'
        );
    }

    public function testGetCustomerPreloadsFields()
    {
        $this->stubResponses([
            $this->getResponse(200, MailboxPayloads::getMailbox(1)),
            $this->getResponse(200, MailboxPayloads::getFields(1)),
        ]);

        $request = (new MailboxRequest())
            ->withFields();

        $mailbox = $this->client->mailboxes()->get(1, $request);
        $fields = $mailbox->getFields();

        $this->assertCount(1, $fields);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/mailboxes/1'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/1/fields'],
        ]);
    }

    public function testGetCustomerPreloadsFolders()
    {
        $this->stubResponses([
            $this->getResponse(200, MailboxPayloads::getMailbox(1)),
            $this->getResponse(200, MailboxPayloads::getFolders(1)),
        ]);

        $request = (new MailboxRequest())
            ->withFolders();

        $mailbox = $this->client->mailboxes()->get(1, $request);
        $folders = $mailbox->getFolders();

        $this->assertCount(1, $folders);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/mailboxes/1'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/1/folders'],
        ]);
    }

    public function testGetMailboxes()
    {
        $this->stubResponse(
            $this->getResponse(200, MailboxPayloads::getMailboxes(1, 10))
        );

        $mailboxes = $this->client->mailboxes()->list();

        $this->assertCount(10, $mailboxes);
        $this->assertInstanceOf(Mailbox::class, $mailboxes[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/mailboxes'
        );
    }

    public function testGetMailboxesPreloadsFields()
    {
        $this->stubResponses([
            $this->getResponse(200, MailboxPayloads::getMailboxes(1, 2)),
            $this->getResponse(200, MailboxPayloads::getFields(1)),
            $this->getResponse(200, MailboxPayloads::getFields(2)),
        ]);

        $request = (new MailboxRequest())
            ->withFields();

        $mailboxes = $this->client->mailboxes()->list($request);

        $this->assertCount(2, $mailboxes);
        $this->assertInstanceOf(Field::class, $mailboxes[0]->getFields()[0]);
        $this->assertInstanceOf(Field::class, $mailboxes[1]->getFields()[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/mailboxes'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/1/fields'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/2/fields'],
        ]);
    }

    public function testGetMailboxesPreloadsFolders()
    {
        $this->stubResponses([
            $this->getResponse(200, MailboxPayloads::getMailboxes(1, 2)),
            $this->getResponse(200, MailboxPayloads::getFolders(1)),
            $this->getResponse(200, MailboxPayloads::getFolders(2)),
        ]);

        $request = (new MailboxRequest())
            ->withFolders();

        $mailboxes = $this->client->mailboxes()->list($request);

        $this->assertCount(2, $mailboxes);
        $this->assertInstanceOf(Folder::class, $mailboxes[0]->getFolders()[0]);
        $this->assertInstanceOf(Folder::class, $mailboxes[1]->getFolders()[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/mailboxes'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/1/folders'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes/2/folders'],
        ]);
    }

    public function testGetMailboxesLazyLoadsPages()
    {
        $this->stubResponses([
            $this->getResponse(200, MailboxPayloads::getMailboxes(1, 20)),
            $this->getResponse(200, MailboxPayloads::getMailboxes(1, 20)),
        ]);

        $mailboxes = $this->client->mailboxes()->list()->getPage(2);

        $this->assertCount(10, $mailboxes);
        $this->assertInstanceOf(Mailbox::class, $mailboxes[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/mailboxes'],
            ['GET', 'https://api.helpscout.net/v2/mailboxes?page=2'],
        ]);
    }
}
