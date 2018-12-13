<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use HelpScout\Api\Conversations\Threads\Thread;
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Entity\LinkedEntityLoader;
use HelpScout\Api\Mailboxes\Mailbox;
use HelpScout\Api\Users\User;

class ConversationLoader extends LinkedEntityLoader
{
    public function load()
    {
        /** @var Conversation $conversation */
        $conversation = $this->getEntity();

        if ($this->shouldLoadResource(ConversationLinks::MAILBOX)) {
            $mailbox = $this->loadResource(Mailbox::class, ConversationLinks::MAILBOX);
            $conversation->setMailbox($mailbox);
        }

        if ($this->shouldLoadResource(ConversationLinks::PRIMARY_CUSTOMER)) {
            $customer = $this->loadResource(Customer::class, ConversationLinks::PRIMARY_CUSTOMER);
            $conversation->setCustomer($customer);
        }

        if ($this->shouldLoadResource(ConversationLinks::CREATED_BY_CUSTOMER)) {
            $createdByCustomer = $this->loadResource(Customer::class, ConversationLinks::CREATED_BY_CUSTOMER);
            $conversation->setCreatedByCustomer($createdByCustomer);
        }

        if ($this->shouldLoadResource(ConversationLinks::CREATED_BY_USER)) {
            $createdByUser = $this->loadResource(User::class, ConversationLinks::CREATED_BY_USER);
            $conversation->setCreatedByUser($createdByUser);
        }

        if ($this->shouldLoadResource(ConversationLinks::ASSIGNEE)) {
            $assignee = $this->loadResource(User::class, ConversationLinks::ASSIGNEE);
            $conversation->setAssignee($assignee);
        }

        if ($this->shouldLoadResource(ConversationLinks::THREADS)) {
            $threads = $this->loadResources(Thread::class, ConversationLinks::THREADS);
            $conversation->setThreads($threads);
        }

        if ($conversation->getStatus() === Status::CLOSED && $this->shouldLoadResource(ConversationLinks::CLOSED_BY)) {
            $closedBy = $this->loadResource(User::class, ConversationLinks::CLOSED_BY);
            $conversation->setClosedBy($closedBy);
        }
    }
}
